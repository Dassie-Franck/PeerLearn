<?php
// ============================================================
//  controllers/message/poll_controller.php
//  Polling léger — retourne les nouveaux messages depuis last_id
//  GET : ?url=poll&avec=X&last_id=Y
//  Réponse JSON — appelé toutes les 3s par le JS de conversation
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

header('Content-Type: application/json; charset=utf-8');
// Pas de cache navigateur
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

$pdo              = get_pdo();
$user_id          = getUserId();
$interlocuteur_id = (int) ($_GET['avec']    ?? 0);
$last_id          = (int) ($_GET['last_id'] ?? 0);

if (!$interlocuteur_id) {
    echo json_encode(['ok' => false, 'messages' => []]);
    exit;
}

// ── Nouveaux messages depuis last_id ────────────────────────
$stmt = $pdo->prepare("
    SELECT
        m.id,
        m.envoyeur_id,
        m.contenu,
        m.date_envoi,
        m.signale
    FROM messages m
    WHERE m.id > :last_id
      AND (
          (m.envoyeur_id = :uid  AND m.destinataire_id = :iid)
       OR (m.envoyeur_id = :iid2 AND m.destinataire_id = :uid2)
      )
    ORDER BY m.id ASC
    LIMIT 50
");
$stmt->execute([
    ':last_id' => $last_id,
    ':uid'     => $user_id,         ':iid'  => $interlocuteur_id,
    ':iid2'    => $interlocuteur_id, ':uid2' => $user_id,
]);
$nouveaux = $stmt->fetchAll();

// ── Marquer comme lus les messages reçus ────────────────────
if (!empty($nouveaux)) {
    $pdo->prepare("
        UPDATE messages
        SET lu = 1
        WHERE destinataire_id = :uid
          AND envoyeur_id     = :iid
          AND lu = 0
    ")->execute([':uid' => $user_id, ':iid' => $interlocuteur_id]);
}

// ── Formater pour le JS ─────────────────────────────────────
$formatted = [];
foreach ($nouveaux as $m) {
    $formatted[] = [
        'id'          => (int) $m['id'],
        'envoyeur_id' => (int) $m['envoyeur_id'],
        'contenu'     => htmlspecialchars($m['contenu'], ENT_QUOTES, 'UTF-8'),
        'date_envoi'  => date('H:i', strtotime($m['date_envoi'])),
        'signale'     => (bool) $m['signale'],
        'moi'         => (int) $m['envoyeur_id'] === $user_id,
    ];
}

// ── Nb total non lus (pour badge navbar) ────────────────────
$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE destinataire_id=:uid AND lu=0");
$stmt2->execute([':uid' => $user_id]);
$total_non_lus = (int) $stmt2->fetchColumn();

echo json_encode([
    'ok'           => true,
    'messages'     => $formatted,
    'total_non_lus'=> $total_non_lus,
]);
exit;