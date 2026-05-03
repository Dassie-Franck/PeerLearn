<?php
// ============================================================
//  controllers/agenda/refuser_controller.php
//  Mentor refuse une demande (en_attente → refusee)
//  POST : session_id, motif (optionnel)
//  Libère aussi le créneau de disponibilité associé
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
$motif      = trim($_POST['motif'] ?? '');

if (!$session_id) {
    setToast('Demande introuvable.', 'error');
    redirect_to('demandes');
}

// ── Vérifie appartenance + statut ─────────────────────────
$stmt = $pdo->prepare("
    SELECT id, statut, apprenant_id, disponibilite_id AS dispo_id
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

// ── Passe la session en "refusee" ─────────────────────────
$upd = $pdo->prepare("
    UPDATE sessions
    SET statut          = 'refusee',
        motif_annulation = :motif,
        updated_at      = NOW()
    WHERE id = :id AND mentor_id = :mentor_id
");
$upd->execute([
    ':motif'     => $motif ?: null,
    ':id'        => $session_id,
    ':mentor_id' => $mentor_id,
]);

// ── Libère le créneau de disponibilité ────────────────────
// Si la session était liée à une dispo, on la remet disponible
if (!empty($session['dispo_id'])) {
    $lib = $pdo->prepare("
        UPDATE disponibilites
        SET est_reservee = 0
        WHERE id = :dispo_id AND mentor_id = :mentor_id
    ");
    $lib->execute([
        ':dispo_id'  => $session['dispo_id'],
        ':mentor_id' => $mentor_id,
    ]);
}

// ── Notification à l'apprenant ────────────────────────────
if ($session['apprenant_id']) {
    $msg_notif = 'Votre demande de session a été refusée par le mentor.';
    if ($motif) {
        $msg_notif .= ' Motif : ' . $motif;
    }
    $notif = $pdo->prepare("
        INSERT INTO notifications (utilisateur_id, type, titre, contenu, lien, lu, created_at)
        VALUES (:uid, 'reservation_annulee', 'Demande refusée',
                :contenu, '/?url=mes-sessions', 0, NOW())
    ");
    $notif->execute([
        ':uid'     => $session['apprenant_id'],
        ':contenu' => $msg_notif,
    ]);
}

setToast('Demande refusée.', 'info');
redirect_to('demandes');