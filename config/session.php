<?php
// ============================================================
//  PeerLearn — config/session.php
//  Démarrage sécurisé de session + helpers d'authentification
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,           // Expire à la fermeture du navigateur
        'path'     => '/',
        'secure'   => false,       // Mettre true en HTTPS production
        'httponly' => true,        // Inaccessible via JS (protection XSS)
        'samesite' => 'Strict',    // Protection CSRF
    ]);
    session_start();
}

// Charge les helpers si nécessaire (setToast(), redirect_to(), h(), ...)
if (!function_exists('h') || !function_exists('setToast') || !function_exists('redirect_to')) {
    require_once dirname(__DIR__) . '/includes/helpers.php';
}

// ============================================================
//  HELPERS D'AUTHENTIFICATION
// ============================================================

/**
 * Vérifie si l'utilisateur est connecté
 */
function estConnecte(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Retourne l'ID de l'utilisateur connecté
 */
function getUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Retourne le rôle de l'utilisateur connecté
 */
function getUserRole(): ?string {
    return $_SESSION['user_role'] ?? $_SESSION['role'] ?? null;
}

/**
 * Retourne les données complètes de l'utilisateur en session
 */
function getUser(): ?array {
    if (!estConnecte()) return null;
    return [
        'id'            => $_SESSION['user_id'] ?? null,
        'nom'           => $_SESSION['user_nom'] ?? $_SESSION['nom'] ?? null,
        'prenom'        => $_SESSION['user_prenom'] ?? null,
        'email'         => $_SESSION['user_email'] ?? null,
        'role'          => $_SESSION['user_role'] ?? $_SESSION['role'] ?? null,
        'est_mentor'    => $_SESSION['user_est_mentor'] ?? $_SESSION['est_mentor'] ?? 0,
        'mentor_valide' => $_SESSION['user_mentor_valide'] ?? $_SESSION['mentor_valide'] ?? 0,
        'photo'         => $_SESSION['user_photo'] ?? null,
    ];
}

/**
 * Vérifie si l'utilisateur est admin
 */
function estAdmin(): bool {
    return estConnecte() && getUserRole() === 'admin';
}

/**
 * Vérifie si l'utilisateur est un mentor validé
 */
function estMentorValide(): bool {
    $isMentor = (isset($_SESSION['user_est_mentor']) && $_SESSION['user_est_mentor'] == 1)
             || (isset($_SESSION['est_mentor']) && $_SESSION['est_mentor'] == 1);

    $isValidMentor = (isset($_SESSION['user_mentor_valide']) && $_SESSION['user_mentor_valide'] == 1)
                  || (isset($_SESSION['mentor_valide']) && $_SESSION['mentor_valide'] == 1);

    return estConnecte() && $isMentor && $isValidMentor;
}

// ============================================================
//  GUARDS D'ACCÈS (à appeler en tête de page)
// ============================================================

/**
 * Redirige vers login si non connecté
 */
function requireConnecte(): void {
    if (!estConnecte()) {
        setToast('Vous devez être connecté pour accéder à cette page.', 'warning');
        redirect_to('login');
    }
}

/**
 * Redirige si non admin
 */
function requireAdmin(): void {
    requireConnecte();
    if (!estAdmin()) {
        setToast('Accès refusé — zone administrateur.', 'error');
        redirect_to('login');
    }
}

/**
 * Redirige si non mentor validé
 */
function requireMentor(): void {
    requireConnecte();
    if (!estMentorValide()) {
        setToast('Accès refusé — profil mentor requis.', 'error');
        redirect_to('dashboard');
    }
}

// ============================================================
//  CSRF
// ============================================================

/**
 * Génère et stocke un token CSRF en session
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF soumis dans un formulaire POST
 */
function verifyCsrfToken(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Vérifie le CSRF et tue la requête si invalide
 */
function requireCsrf(): void {
    if (!verifyCsrfToken()) {
        http_response_code(403);
        setToast('Requête invalide — token CSRF manquant.', 'error');
        redirect_to('login');
    }
}

/**
 * Génère le champ HTML caché CSRF à insérer dans chaque formulaire POST
 */
function csrfField(): string {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' .h($token) . '">';
}