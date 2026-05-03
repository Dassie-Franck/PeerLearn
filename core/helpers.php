<?php
// ============================================================
//  includes/helpers.php — Fonctions utilitaires globales
// ============================================================

// Protection XSS
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function h($str) {
    return e($str);
}

// Redirection sécurisée
function redirect_to($page) {
    header('Location: ' . APP_URL . '/?url=' . ltrim($page, '/'));
    exit;
}

function redirect($page) {
    if (preg_match('~^https?://~i', $page)) {
        $location = $page;
    } elseif (strpos($page, '/') === 0) {
        $location = APP_URL . $page;
    } else {
        $location = APP_URL . '/?url=' . ltrim($page, '/');
    }
    header('Location: ' . $location);
    exit;
}

// Vérifie si connecté
function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

// Vérifie admin
function is_admin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

// Vérifie mentor valide
function is_mentor() {
    return (
        ($_SESSION['est_mentor'] ?? 0) == 1 &&
        ($_SESSION['mentor_valide'] ?? 0) == 1
    );
}

// ==========================
// TOAST SYSTEM (PROPRE)
// ==========================

// Succès
function set_success($msg) {
    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => $msg
    ];
}

// Erreur
function set_error($msg) {
    $_SESSION['toast'] = [
        'type' => 'error',
        'message' => $msg
    ];
}

// Info
function set_info($msg) {
    $_SESSION['toast'] = [
        'type' => 'info',
        'message' => $msg
    ];
}

function setToast(string $message, string $type = 'info'): void {
    $_SESSION['toast'] = [
        'message' => $message,
        'type'    => $type
    ];
}