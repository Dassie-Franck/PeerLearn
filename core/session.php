<?php
// ============================================================
//  core/session.php
//  Gestion de la session utilisateur
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ------------------------------------------------------------
//  Connexion / déconnexion
// ------------------------------------------------------------

function loginUser(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user_id']     = $user['id'];
    $_SESSION['user_nom']    = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_role']   = $user['role'];
    $_SESSION['est_mentor']  = (bool) $user['est_mentor'];
    $_SESSION['logged_in']   = true;
}

function destroySession(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
}

// ------------------------------------------------------------
//  Accès restreint
// ------------------------------------------------------------

function requireLogin(string $redirect = '/controllers/auth/login_controller.php'): void
{
    if (empty($_SESSION['logged_in'])) {
        header('Location: ' . $redirect);
        exit;
    }
}

function requireAdmin(): void
{
    requireLogin();

    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        header('Location: /controllers/auth/login_controller.php');
        exit;
    }
}

// ------------------------------------------------------------
//  Helpers
// ------------------------------------------------------------

function isLoggedIn(): bool
{
    return !empty($_SESSION['logged_in']);
}

function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function redirectToDashboard(): void
{
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        header('Location: /controllers/admin/dashboard_controller.php');
    } elseif (!empty($_SESSION['est_mentor'])) {
        header('Location: /controllers/mentor/dashboard_controller.php');
    } else {
        header('Location: /controllers/etudiant/dashboard_controller.php');
    }
    exit;
}

// ------------------------------------------------------------
//  Messages flash (1 seul affichage)
// ------------------------------------------------------------

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
