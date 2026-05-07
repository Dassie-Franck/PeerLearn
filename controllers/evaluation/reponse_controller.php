<?php
// ============================================================
//  controllers/evaluation/reponse_controller.php
// ============================================================

require_once BASE_PATH . '/models/evaluation_model.php';

require_logged_in();

// Seul un mentor peut répondre
if (!is_mentor()) {
    setToast('Accès réservé aux mentors.', 'error');
    redirect_to('mentor');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('mentor');
}

if (!csrf_verify()) {
    setToast('Token invalide.', 'error');
    redirect_to('mentor');
}

$evaluation_id = (int)($_POST['evaluation_id'] ?? 0);
$reponse       = trim($_POST['reponse']        ?? '');
$mentor_id     = $_SESSION['user_id'];

if (!$evaluation_id || empty($reponse)) {
    setToast('Réponse invalide.', 'error');
    redirect_to('mentor');
}

$ok = repondre_evaluation($evaluation_id, $mentor_id, $reponse);

if ($ok) {
    setToast('Votre réponse a été publiée.', 'success');
} else {
    setToast('Impossible de répondre à cette évaluation.', 'error');
}

redirect_to('mentor');
