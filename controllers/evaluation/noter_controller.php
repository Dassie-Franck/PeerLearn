<?php
// ============================================================
//  controllers/evaluation/noter_controller.php
//  BF32 — Étudiant évalue un mentor après une session terminée
// ============================================================
require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/models/evaluation_model.php';

require_logged_in();

$apprenant_id = getUserId();
$session_id   = (int)($_GET['session_id'] ?? 0);

if ($session_id <= 0) {
    setToast('Session invalide.', 'error');
    redirect_to('mes-sessions');
}

// Vérifie que la session est bien terminée et non encore évaluée
$session = get_session_evaluable($session_id, $apprenant_id);

if (!$session) {
    setToast('Cette session ne peut pas être évaluée (déjà notée ou non terminée).', 'error');
    redirect_to('mes-sessions');
}

// ── Traitement POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $note        = (int)($_POST['note']        ?? 0);
    $commentaire = trim($_POST['commentaire']  ?? '');

    // Validation
    if ($note < 1 || $note > 5) {
        setToast('Veuillez sélectionner une note entre 1 et 5 étoiles.', 'error');
        redirect_to('evaluer&session_id=' . $session_id);
    }

    // Double-check race condition
    if (a_deja_evalue($session_id)) {
        setToast('Cette session a déjà été évaluée.', 'error');
        redirect_to('mes-sessions');
    }

    $ok = soumettre_evaluation(
        $session_id,
        $apprenant_id,
        $session['mentor_id'],
        $note,
        $commentaire
    );

    if ($ok) {
        setToast('Merci pour votre évaluation !', 'success');
        redirect_to('fiche-mentor&id=' . $session['mentor_id']);
    } else {
        setToast('Une erreur est survenue, veuillez réessayer.', 'error');
        redirect_to('evaluer&session_id=' . $session_id);
    }
}

$page_active = 'sessions';
require_once BASE_PATH . '/views/evaluations/formulaire.php';
