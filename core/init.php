<?php
// ============================================================
//  PeerLearn — core/init.php
//  Point d'entrée unique : à inclure EN PREMIER dans chaque page
//
//  Usage :
//    require_once dirname(__DIR__, 2) . '/core/init.php';
//    // (adapter le niveau selon la profondeur du fichier)
// ============================================================

// Chemin racine du projet (là où se trouve ce fichier core/)
define('ROOT', dirname(__DIR__));

// Charge la connexion PDO
require_once ROOT . '/config/database.php';

// Charge les helpers (h(), redirect(), setToast()...)
require_once ROOT . '/core/helpers.php';

// Démarre la session et charge les guards/CSRF
require_once ROOT . '/config/session.php'; 
