<?php
// ============================================================
//  controllers/auth/logout_controller.php
//  Fonctionnalité : F03 — Déconnexion sécurisée
//
//  helpers.php est déjà chargé par index.php :
//  set_success() et redirect_to() sont disponibles ici
//  sans aucun require supplémentaire.
// ============================================================

// 1. Vider les données de session
$_SESSION = [];

// 2. Supprimer le cookie de session côté navigateur
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// 3. Détruire la session côté serveur
session_destroy();

// 4. Ouvrir une nouvelle session propre pour stocker le toast
//    session_destroy() ferme la session en cours ;
//    on doit en démarrer une nouvelle avant d'écrire dans $_SESSION.
session_start();
session_regenerate_id(true);

// 5. Toast + redirection vers login
set_success('Vous avez été déconnecté avec succès.');
redirect_to('login');
