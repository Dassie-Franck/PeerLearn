<?php
// ============================================================
//  controllers/evaluation/noter_controller.php
// ============================================================

require_once BASE_PATH . '/models/session_model.php';
require_once BASE_PATH . '/models/evaluation_model.php';

require_logged_in();

$user_id    = $_SESSION['user_id'];
$session_id = (int)($_GET['session_id'] ?? 0);

if (!$session_id) {
    setToast('Session non spécifiée.', 'error');
    redirect_to('mes-sessions');
}

// Récupère la session
$session = get_session_par_id($session_id);

if (!$session) {
    setToast('Session introuvable.', 'error');
    redirect_to('mes-sessions');
}

// Vérifie que l'utilisateur est bien l'apprenant
if ($session['apprenant_id'] != $user_id) {
    setToast("Vous n'êtes pas autorisé à évaluer cette session.", 'error');
    redirect_to('mes-sessions');
}

// Vérifie que c'est pas le mentor qui s'auto-évalue
if ($session['mentor_id'] == $user_id) {
    setToast("Vous ne pouvez pas évaluer votre propre session.", 'error');
    redirect_to('mes-sessions');
}

// Vérifie que la session est terminée
if ($session['statut'] !== 'terminee') {
    setToast("Cette session n'est pas encore terminée.", 'error');
    redirect_to('mes-sessions');
}

// Vérifie que l'évaluation n'existe pas déjà
if (session_deja_evaluee($session_id, $user_id)) {
    setToast("Vous avez déjà évalué cette session.", 'warning');
    redirect_to('mes-sessions');
}

// ── Traitement POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        setToast('Token invalide.', 'error');
        redirect_to('evaluer?session_id=' . $session_id);
    }

    $note        = (int)($_POST['note']       ?? 0);
    $commentaire = trim($_POST['commentaire'] ?? '');

    if ($note < 1 || $note > 5) {
        setToast('Veuillez sélectionner une note entre 1 et 5.', 'error');
        redirect_to('evaluer?session_id=' . $session_id);
    }

    $result = creer_evaluation(
        $session_id,
        $session['mentor_id'],
        $user_id,
        $note,
        $commentaire
    );

    if (isset($result['success'])) {
        setToast('Merci pour votre évaluation !', 'success');
        redirect_to('mes-sessions');
    } else {
        setToast($result['erreur'] ?? 'Une erreur est survenue.', 'error');
        redirect_to('evaluer?session_id=' . $session_id);
    }
}

// ── Affichage formulaire (GET) ───────────────────────────────
$page_active = 'sessions';
require_once BASE_PATH . '/views/evaluations/noter.php';
