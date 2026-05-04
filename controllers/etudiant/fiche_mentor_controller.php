<?php
// ============================================================
//  controllers/etudiant/fiche_mentor_controller.php
//  BF09 — Fiche publique d'un mentor
// ============================================================

require_once BASE_PATH . '/models/profil_mentor_model.php';
require_once BASE_PATH . '/models/session_model.php';
require_once BASE_PATH . '/models/matiere_model.php';
require_once BASE_PATH . '/models/disponibilite_model.php';
require_once BASE_PATH . '/models/evaluation_model.php';

require_logged_in();

$mentor_id = (int)($_GET['id'] ?? 0);

if ($mentor_id <= 0) {
    setToast('Mentor introuvable.', 'error');
    redirect_to('recherche');
}

$mentor         = get_fiche_mentor($mentor_id);

if (!$mentor) {
    setToast('Ce profil mentor n\'existe pas ou n\'est plus disponible.', 'error');
    redirect_to('recherche');
}

$matieres       = get_matieres_mentor($mentor_id);
$disponibilites = get_disponibilites_mentor_profile($mentor_id);
$evaluations    = get_evaluations_mentor($mentor_id);

$page_active = 'recherche';

require_once BASE_PATH . '/views/etudiant/fiche_mentor.php';
