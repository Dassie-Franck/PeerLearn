<?php
// ============================================================
//  public/index.php — Point d entree unique
// ============================================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/app.php';

// Demarrage session securise
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

require_once '../config/database.php';
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/config/session.php';

// ============================================================
//  ROUTES — url => controller
// ============================================================
$routes = [

    // AUTH
    'login'    => 'auth/login_controller.php',
    'register' => 'auth/register_controller.php',
    'logout'   => 'auth/logout_controller.php',
    'forgot'   => 'auth/forgot_controller.php',

    // ETUDIANT
    'dashboard'      => 'etudiant/dashboard_controller.php',
    'profil'         => 'etudiant/profil_controller.php',
    'demande-mentor' => 'etudiant/demande_mentor_controller.php',
    'recherche'      => 'etudiant/recherche_controller.php',
    'fiche-mentor'   => 'etudiant/fiche_mentor_controller.php',

    // AGENDA
    'mes-sessions'   => 'agenda/mes_sessions_controller.php',
    'reserver'       => 'agenda/reserver_controller.php',
    'confirmer'      => 'agenda/confirmer_controller.php',
    'refuser'        => 'agenda/refuser_controller.php',
    'annuler'        => 'agenda/annuler_controller.php',
    'disponibilites' => 'agenda/disponibiliter_controller.php',
    'ajouter-dispo'  => 'agenda/ajouter_dispo_controller.php',
    'suppr-dispo'    => 'agenda/supprimer_dispo_controller.php',

    // MENTOR
    'mentor'        => 'mentor/dashboard_controller.php',
    'mentor-profil' => 'mentor/profil_controller.php',
    'demandes'      => 'mentor/demandes_controller.php',
    'mentor-statut' => 'mentor/statut_controller.php',

    // MESSAGES
    'messages'     => 'messages/inbox_controller.php',
    'conversation' => 'messages/conversation_controller.php',
    'envoyer-msg'  => 'messages/envoyer_controller.php',
    'poll'         => 'messages/poll_controller.php',
    'signaler-msg' => 'messages/signaler_controller.php',

    // EVALUATIONS
    'evaluer'      => 'evaluation/noter_controller.php',
    'repondre-avis'=> 'evaluation/reponse_controller.php',

    // ADMIN
    'admin'              => 'admin/dashboard_controller.php',
    'admin-users'        => 'admin/utilisateurs_controller.php',
    'admin-toggle-user'  => 'admin/toggle_user_controller.php',
    'admin-valider'      => 'admin/valider_mentor_controller.php',
    'admin-rejeter'      => 'admin/rejeter_mentor_controller.php',
    'admin-signalements' => 'admin/signalements_controller.php',
    'admin-suppr-avis'   => 'admin/supprimer_avis_controller.php',
    'admin-matieres'     => 'admin/matieres_controller.php',
    'admin-journal'      => 'admin/journal_controller.php',

    // HOME
    ''        => 'home_controller.php',
    'home'    => 'home_controller.php',
    'accueil' => 'home_controller.php',
];

// ============================================================
//  DISPATCH
// ============================================================
$url  = trim(filter_var($_GET['url'] ?? '', FILTER_SANITIZE_URL), '/');
$file = BASE_PATH . '/controllers/' . ($routes[$url] ?? '');

if (isset($routes[$url]) && file_exists($file)) {
    require_once $file;
} else {
    http_response_code(404);
    require_once BASE_PATH . '/views/errors/404.php';
}