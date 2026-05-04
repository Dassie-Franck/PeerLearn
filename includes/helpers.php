<?php
// ============================================================
//  includes/helpers.php
// ============================================================

// --- Protection XSS ---
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function h(string $str): string {
    return e($str);
}

// --- Redirection ---
function redirect_to(string $page): void {
    header('Location: ' . APP_URL . '/?url=' . ltrim($page, '/'));
    exit;
}

// --- Authentification ---
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function is_mentor(): bool {
    return ($_SESSION['est_mentor']    ?? 0) == 1
        && ($_SESSION['mentor_valide'] ?? 0) == 1;
}

// --- Guards ---
function require_logged_in(): void {
    if (!is_logged_in()) {
        set_warning('Vous devez etre connecte pour acceder a cette page.');
        redirect_to('login');
    }
}

function require_admin(): void {
    require_logged_in();
    if (!is_admin()) {
        set_error('Acces refuse.');
        redirect_to('dashboard');
    }
}

function require_mentor(): void {
    require_logged_in();
    if (!is_mentor()) {
        set_error('Acces refuse — profil mentor requis.');
        redirect_to('dashboard');
    }
}

// --- Toast (flash session) ---
function set_success(string $msg): void {
    $_SESSION['toast'] = ['type' => 'success', 'message' => $msg];
}

function set_error(string $msg): void {
    $_SESSION['toast'] = ['type' => 'error', 'message' => $msg];
}

function set_warning(string $msg): void {
    $_SESSION['toast'] = ['type' => 'warning', 'message' => $msg];
}

function set_info(string $msg): void {
    $_SESSION['toast'] = ['type' => 'info', 'message' => $msg];
}

function setToast(string $message, string $type = 'info'): void {
    $_SESSION['toast'] = [
        'message' => $message,
        'type'    => $type,
    ];
}

// --- CSRF ---
function csrf_generate(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(): bool {
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_generate() . '">';
}