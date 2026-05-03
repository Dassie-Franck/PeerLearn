<?php
// ============================================================
//  controllers/auth/register_controller.php
//  F02 — Inscription utilisateur
// ============================================================

require_once BASE_PATH . '/models/user_model.php';

if (is_logged_in()) {
    redirect_to('dashboard');
}

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error('Requete invalide.');
        redirect_to('register');
    }

    $nom      = trim($_POST['nom']                  ?? '');
    $prenom   = trim($_POST['prenom']               ?? '');
    $email    = trim($_POST['email']                ?? '');
    $mdp      = trim($_POST['mot_de_passe']         ?? '');
    $mdp_conf = trim($_POST['mot_de_passe_confirm'] ?? '');

    $erreurs = [];
    if (empty($nom))                                 $erreurs[] = 'Le nom est obligatoire.';
    if (empty($prenom))                              $erreurs[] = 'Le prenom est obligatoire.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $erreurs[] = 'Email invalide.';
    if (strlen($mdp) < 8)                            $erreurs[] = 'Mot de passe : 8 caracteres minimum.';
    if ($mdp !== $mdp_conf)                          $erreurs[] = 'Les mots de passe ne correspondent pas.';
    if (email_existe($email))                        $erreurs[] = 'Cet email est deja utilise.';

    if (!empty($erreurs)) {
        set_error(implode(' ', $erreurs));
        $_SESSION['old'] = compact('nom', 'prenom', 'email');
        redirect_to('register');
    }

    $id = creer_utilisateur($nom, $prenom, $email, $mdp);

    session_regenerate_id(true);
    $_SESSION['user_id']       = $id;
    $_SESSION['role']          = 'etudiant';
    $_SESSION['nom']           = $prenom . ' ' . $nom;
    $_SESSION['est_mentor']    = 0;
    $_SESSION['mentor_valide'] = 0;
    $_SESSION['statut']        = 'actif';

    set_success('Bienvenue sur PeerLearn, ' . $prenom . ' !');
    redirect_to('dashboard');
}

require_once BASE_PATH . '/views/auth/register.php';