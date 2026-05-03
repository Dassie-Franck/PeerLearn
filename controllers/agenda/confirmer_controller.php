<?php
// ============================================================
//  controllers/agenda/confirmer_controller.php
//  Mentor confirme une demande de session (en_attente → confirmee)
//  POST : session_id, lien_session (optionnel si présentiel)
// ============================================================

require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/models/notification_model.php';
requireMentor();
requireCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('demandes');
}

$pdo        = get_pdo();
$mentor_id  = getUserId();
$session_id = (int) ($_POST['session_id'] ?? 0);
$lien       = trim($_POST['lien_session'] ?? '');

if (!$session_id) {
    setToast('Demande introuvable.', 'error');
    redirect_to('demandes');
}

// ── Vérifie que cette session appartient bien à ce mentor
//    et qu'elle est encore en attente ─────────────────────
$stmt = $pdo->prepare("
    SELECT id, mode_session, statut
    FROM sessions
    WHERE id = :id AND mentor_id = :mentor_id
");
$stmt->execute([':id' => $session_id, ':mentor_id' => $mentor_id]);
$session = $stmt->fetch();

if (!$session) {
    setToast('Session introuvable ou accès non autorisé.', 'error');
    redirect_to('demandes');
}

if ($session['statut'] !== 'en_attente') {
    setToast('Cette demande a déjà été traitée.', 'warning');
    redirect_to('demandes');
}

// ── Lien obligatoire pour les sessions en ligne ────────────
if ($session['mode_session'] === 'en_ligne' && empty($lien)) {
    setToast('Veuillez fournir un lien de visioconférence pour une session en ligne.', 'error');
    redirect_to('demandes');
}

// ── Mise à jour ────────────────────────────────────────────
$upd = $pdo->prepare("
    UPDATE sessions
    SET statut       = 'confirmee',
        lien_session = :lien,
        updated_at   = NOW()
    WHERE id = :id AND mentor_id = :mentor_id
");
$upd->execute([
    ':lien'      => $lien ?: null,
    ':id'        => $session_id,
    ':mentor_id' => $mentor_id,
]);

// ── Notification à l'apprenant ────────────────────────────
// Récupère l'apprenant_id pour la notif
$row = $pdo->prepare("SELECT apprenant_id FROM sessions WHERE id = :id");
$row->execute([':id' => $session_id]);
$apprenant_id = $row->fetchColumn();

if ($apprenant_id) {
    $notif = $pdo->prepare("
        INSERT INTO notifications (utilisateur_id, type, titre, contenu, lien, lu, created_at)
        VALUES (:uid, 'reservation_confirmee', 'Session confirmée !',
                'Votre session de tutorat a été confirmée par le mentor.',
                :lien_notif, 0, NOW())
    ");
    $notif->execute([
        ':uid'       => $apprenant_id,
        ':lien_notif' => '/?url=mes-sessions',
    ]);
}

setToast('Session confirmée avec succès.', 'success');
redirect_to('demandes');