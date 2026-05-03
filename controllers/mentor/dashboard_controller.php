<?php
// controllers/mentor/dashboard_controller.php
// ============================================================

require_once BASE_PATH . '/models/profil_mentor_model.php';
require_once BASE_PATH . '/models/session_model.php';

require_logged_in();
require_mentor();

$user_id = $_SESSION['user_id'];

marquer_sessions_terminees();

$profil_mentor = get_profil_mentor($user_id);
$stats         = get_stats_mentor($user_id);
$demandes      = get_demandes_en_attente_mentor($user_id);

//  CORRECTION : Récupérer UNIQUEMENT les sessions où le mentor est MENTOR
$sessions_a_venir_raw = get_sessions_a_venir($user_id, 10);
$sessions_a_venir = array_filter($sessions_a_venir_raw, function($session) use ($user_id) {
    return $session['mentor_id'] == $user_id;
});
$sessions_a_venir = array_slice($sessions_a_venir, 0, 5);

$page_active = 'mentor';

require_once BASE_PATH . '/views/mentor/dashboard.php';