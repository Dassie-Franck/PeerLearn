<?php
// ============================================================
//  controllers/message/signaler_controller.php
//  Signale un message comme inapproprié
//  POST : message_id, csrf_token
//  Réponse JSON
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

header('Content-Type: application/json; charset=utf-8');

if (!verifyCsrfToken()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erreur' => 'Token invalide.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'erreur' => 'Méthode non autorisée.']);
    exit;
}

$pdo        = get_pdo();
$user_id    = getUserId();
$message_id = (int) ($_POST['message_id'] ?? 0);

if (!$message_id) {
    echo json_encode(['ok' => false, 'erreur' => 'Message introuvable.']);
    exit;
}

// ── Vérifie que l'user est bien le destinataire du message ──
// (on ne signale que ce qu'on reçoit)
$stmt = $pdo->prepare("
    SELECT id FROM messages
    WHERE id = :id AND destinataire_id = :uid AND signale = 0
    LIMIT 1
");
$stmt->execute([':id' => $message_id, ':uid' => $user_id]);

if (!$stmt->fetch()) {
    echo json_encode(['ok' => false, 'erreur' => 'Action non autorisée ou déjà signalé.']);
    exit;
}

// ── Marquer comme signalé ───────────────────────────────────
$pdo->prepare("UPDATE messages SET signale = 1 WHERE id = :id")
    ->execute([':id' => $message_id]);

// ── Notifier les admins ─────────────────────────────────────
$admins = $pdo->query("SELECT id FROM utilisateurs WHERE role = 'admin' AND statut = 'actif'")->fetchAll();
$notif  = $pdo->prepare("
    INSERT INTO notifications
        (utilisateur_id, type, titre, contenu, lien, lu, created_at)
    VALUES
        (:uid, 'nouveau_message',
         'Message signalé',
         :contenu,
         '/?url=admin-signalements',
         0, NOW())
");
foreach ($admins as $admin) {
    $notif->execute([
        ':uid'     => $admin['id'],
        ':contenu' => 'Un message a été signalé comme inapproprié (ID #' . $message_id . ').',
    ]);
}

echo json_encode(['ok' => true]);
exit;