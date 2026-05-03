<?php
// controllers/etudiant/dashboard_controller.php
// ============================================================

require_once BASE_PATH . '/models/user_model.php';
require_once BASE_PATH . '/models/session_model.php';

require_logged_in();

$user_id = $_SESSION['user_id'];

// Mise a jour automatique des sessions terminees
marquer_sessions_terminees();

// Donnees pour la vue
$utilisateur = trouver_utilisateur_par_id($user_id);

//  CORRECTION : Récupérer UNIQUEMENT les sessions où l'étudiant est APPRENANT
$sessions_a_venir_raw = get_sessions_a_venir($user_id, 10);  // On prend plus de marge
$sessions_a_venir = array_filter($sessions_a_venir_raw, function($session) use ($user_id) {
    return $session['apprenant_id'] == $user_id;
});
$sessions_a_venir = array_slice($sessions_a_venir, 0, 5);  // Garder seulement 5

$stats       = get_stats_etudiant($user_id);
$page_active = 'dashboard';

require_once BASE_PATH . '/views/etudiant/dashboard.php';