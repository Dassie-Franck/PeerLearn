<?php
// ============================================================
//  controllers/auth/login_controller.php
//  F01 — Connexion utilisateur
// ============================================================

require_once BASE_PATH . '/models/user_model.php';

// Redirige si deja connecte
if (is_logged_in()) {
    redirect_to(is_admin() ? 'admin' : 'dashboard');
}

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error('Requete invalide.');
        redirect_to('login');
    }

    $email = trim($_POST['email']        ?? '');
    $mdp   = trim($_POST['mot_de_passe'] ?? '');

    if (empty($email) || empty($mdp)) {
        set_error('Veuillez remplir tous les champs.');
        $_SESSION['old'] = ['email' => $email];
        redirect_to('login');
    }

    $user = trouver_utilisateur_par_email($email);

    if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
        set_error('Email ou mot de passe incorrect.');
        $_SESSION['old'] = ['email' => $email];
        redirect_to('login');
    }

    if ($user['statut'] !== 'actif') {
        set_error('Votre compte est suspendu. Contactez l administrateur.');
        redirect_to('login');
    }

    // Ouverture de session
    session_regenerate_id(true);
    $_SESSION['user_id']       = $user['id'];
    $_SESSION['role']          = $user['role'];
    $_SESSION['nom']           = $user['prenom'] . ' ' . $user['nom'];
    $_SESSION['est_mentor']    = $user['est_mentor'];
    $_SESSION['mentor_valide'] = $user['mentor_valide'];
    $_SESSION['statut']        = $user['statut'];

    set_success('Bon retour, ' . $user['prenom'] . ' !');
    redirect_to(is_admin() ? 'admin' : 'dashboard');
}

require_once BASE_PATH . '/views/auth/login.php';