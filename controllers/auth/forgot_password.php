<?php
// ============================================================
//  controllers/auth/forgot_controller.php
//  Fonctionnalite : F04 — Reinitialisation mot de passe
// ============================================================

require_once APP_ROOT . '/models/user_model.php';

if (is_logged_in()) {
    redirect_to('dashboard');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error("Requete invalide.");
        redirect_to('forgot');
    }

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_error("Veuillez entrer une adresse email valide.");
        redirect_to('forgot');
    }

    $utilisateur = trouver_utilisateur_par_email($email);

    // Securite : on affiche toujours le meme message
    // pour ne pas confirmer si l email existe ou non
    if ($utilisateur) {
        $token      = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', time() + 3600);

        // Stocke le token temporairement en session
        $_SESSION['reset_token']      = $token;
        $_SESSION['reset_email']      = $email;
        $_SESSION['reset_expiration'] = $expiration;

        $lien  = APP_URL . '/reset?token=' . $token;
        $sujet = APP_NAME . " — Reinitialisation de votre mot de passe";
        $corps = "
            <p>Bonjour " . e($utilisateur['prenom']) . ",</p>
            <p>Cliquez sur le lien ci-dessous pour reinitialiser votre mot de passe :</p>
            <p><a href='{$lien}'>{$lien}</a></p>
            <p>Ce lien est valable <strong>1 heure</strong>.</p>
            <p>Si vous n etes pas a l origine de cette demande, ignorez cet email.</p>
        ";

        require_once APP_ROOT . '/includes/mailer.php';
        send_mail($email, $sujet, $corps);
    }

    set_success("Si cet email existe, un lien de reinitialisation vous a ete envoye.");
    redirect_to('forgot');
}

require_once APP_ROOT . '/views/auth/forgot.php';
