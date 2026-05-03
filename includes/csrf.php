<?php
// ============================================================
//  includes/csrf.php — Protection CSRF
// ============================================================

// Genere un token CSRF unique par session
function csrf_generate() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verifie que le token CSRF du formulaire est valide
function csrf_verify() {
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Retourne le champ hidden a inserer dans chaque formulaire POST
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_generate() . '">';
}