<?php
// ============================================================
//  controllers/etudiant/recherche_controller.php
//  BF10, BF11, BF12, BF13 — Recherche de mentors
// ============================================================

require_once BASE_PATH . '/models/profil_mentor_model.php';

require_logged_in();

// Lecture des filtres GET (sanitisés)
$filtres = [
    'matiere'  => htmlspecialchars(trim($_GET['matiere']  ?? ''), ENT_QUOTES, 'UTF-8'),
    'nom'      => htmlspecialchars(trim($_GET['nom']      ?? ''), ENT_QUOTES, 'UTF-8'),
    'note_min' => in_array($_GET['note_min'] ?? '', ['1','2','3','4','5']) ? $_GET['note_min'] : '',
    'mode'     => in_array($_GET['mode']     ?? '', ['presentiel','en_ligne'])  ? $_GET['mode']     : '',
    'tri'      => in_array($_GET['tri']      ?? '', ['note','nom','sessions'])  ? $_GET['tri']      : 'note',
];
$filters = $filtres;

$mentors  = get_mentors_valides($matieres_id = $filtres['matiere'] ?: null, $note_min = $filtres['note_min'] ?: null, $recherche = $filtres['nom'] ?: null);

$page_active = 'recherche';

require_once BASE_PATH . '/views/etudiant/recherche.php';