<?php
// ============================================================
//  controllers/message/envoyer_controller.php
//  Envoie un message — répond en JSON (appelé en fetch/AJAX)
//  POST : destinataire_id, contenu, csrf_token
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

header('Content-Type: application/json; charset=utf-8');

// ── Validation CSRF ─────────────────────────────────────────
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

$pdo              = get_pdo();
$envoyeur_id      = getUserId();
$destinataire_id  = (int) ($_POST['destinataire_id'] ?? 0);
$contenu          = trim($_POST['contenu'] ?? '');

// ── Validations ─────────────────────────────────────────────
if (!$destinataire_id || $destinataire_id === $envoyeur_id) {
    echo json_encode(['ok' => false, 'erreur' => 'Destinataire invalide.']);
    exit;
}

if (empty($contenu)) {
    echo json_encode(['ok' => false, 'erreur' => 'Le message ne peut pas être vide.']);
    exit;
}

if (mb_strlen($contenu) > 2000) {
    echo json_encode(['ok' => false, 'erreur' => 'Message trop long (2000 caractères max).']);
    exit;
}

// ── Vérifie que le destinataire existe et est actif ─────────
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = :id AND statut = 'actif' LIMIT 1");
$stmt->execute([':id' => $destinataire_id]);
if (!$stmt->fetch()) {
    echo json_encode(['ok' => false, 'erreur' => 'Destinataire introuvable.']);
    exit;
}

// ── Insertion du message ─────────────────────────────────────
$ins = $pdo->prepare("
    INSERT INTO messages (envoyeur_id, destinataire_id, contenu, date_envoi)
    VALUES (:env, :dest, :contenu, NOW())
");
$ins->execute([
    ':env'     => $envoyeur_id,
    ':dest'    => $destinataire_id,
    ':contenu' => $contenu,
]);
$msg_id = (int) $pdo->lastInsertId();

// ── Notification au destinataire ────────────────────────────
// Vérifie s'il a déjà une notif non lue pour ce contact
$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM notifications
    WHERE utilisateur_id = :uid
      AND type           = 'nouveau_message'
      AND lu             = 0
      AND lien           LIKE :lien
");
$stmt->execute([
    ':uid'  => $destinataire_id,
    ':lien' => '%avec=' . $envoyeur_id . '%',
]);
$deja_notif = (int) $stmt->fetchColumn();

if (!$deja_notif) {
    $envoyeur_nom = trim(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? ''));
    $notif = $pdo->prepare("
        INSERT INTO notifications
            (utilisateur_id, type, titre, contenu, lien, lu, created_at)
        VALUES
            (:uid, 'nouveau_message', :titre, :contenu, :lien, 0, NOW())
    ");
    $notif->execute([
        ':uid'     => $destinataire_id,
        ':titre'   => 'Nouveau message de ' . $envoyeur_nom,
        ':contenu' => mb_substr($contenu, 0, 100) . (mb_strlen($contenu) > 100 ? '…' : ''),
        ':lien'    => '/?url=conversation&avec=' . $envoyeur_id,
    ]);
}

// ── Réponse JSON avec le message inséré ─────────────────────
echo json_encode([
    'ok'         => true,
    'message'    => [
        'id'          => $msg_id,
        'envoyeur_id' => $envoyeur_id,
        'contenu'     => htmlspecialchars($contenu, ENT_QUOTES, 'UTF-8'),
        'date_envoi'  => date('H:i'),
    ],
]);
exit;