<?php
// ============================================================
//  controllers/etudiant/recherche_controller.php
//  Recherche de mentors
// ============================================================

require_once BASE_PATH . '/models/profil_mentor_model.php'; // Assure-toi que c'est le bon fichier

require_logged_in();

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

// Lecture des filtres GET
$filtres = [
    'matiere'  => htmlspecialchars(trim($_GET['matiere'] ?? ''), ENT_QUOTES, 'UTF-8'),
    'nom'      => htmlspecialchars(trim($_GET['nom'] ?? ''), ENT_QUOTES, 'UTF-8'),
    'note_min' => in_array($_GET['note_min'] ?? '', ['1','2','3','4','5']) ? $_GET['note_min'] : '',
    'mode'     => in_array($_GET['mode'] ?? '', ['presentiel','en_ligne']) ? $_GET['mode'] : '',
    'tri'      => in_array($_GET['tri'] ?? '', ['note','nom','sessions']) ? $_GET['tri'] : 'note',
];
$filters = $filtres;

//  Appel de la fonction avec exclusion de l'utilisateur connecté
$mentors = rechercher_mentors($filtres, $user_id);

$page_active = 'recherche';

require_once BASE_PATH . '/views/etudiant/recherche.php';