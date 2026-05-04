<?php
// ============================================================
//  controllers/auth/login_controller.php
//  F01 — Connexion utilisateur
// ============================================================

require_once BASE_PATH . '/models/user_model.php';

// Redirige si déjà connecté
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

    // ── Vérification mot de passe ────────────────────────────
    // password_verify() prend le cost depuis le hash stocké.
    // Si un ancien compte a été hashé avec cost > 10, cette ligne
    // peut être lente sur o2switch. Le rehash ci-dessous corrige
    // ça immédiatement après le premier login réussi.
    if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
        set_error('Email ou mot de passe incorrect.');
        $_SESSION['old'] = ['email' => $email];
        redirect_to('login');
    }

    if ($user['statut'] !== 'actif') {
        set_error('Votre compte est suspendu. Contactez l administrateur.');
        redirect_to('login');
    }

    // ── Rehash automatique si cost trop élevé ───────────────
    // Migre silencieusement les anciens hashs (cost 12/14)
    // vers cost 10 au premier login réussi.
    if (password_needs_rehash($user['mot_de_passe'], PASSWORD_BCRYPT, BCRYPT_OPTIONS)) {
        $pdo = get_pdo();
        $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :mdp WHERE id = :id")
            ->execute([
                ':mdp' => password_hash($mdp, PASSWORD_BCRYPT, BCRYPT_OPTIONS),
                ':id'  => $user['id'],
            ]);
    }

    // ── Ouverture de session ─────────────────────────────────
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