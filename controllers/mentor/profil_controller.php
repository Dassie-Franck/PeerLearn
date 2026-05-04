<?php
// ============================================================
//  controllers/mentor/profil_controller.php
//  F11 — Modifier bio + matieres enseignees
// ============================================================

require_once BASE_PATH . '/models/profil_mentor_model.php';
require_once BASE_PATH . '/models/matiere_model.php';
require_once BASE_PATH . '/models/user_model.php';

require_logged_in();
require_mentor();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error('Requete invalide.');
        redirect_to('mentor-profil');
    }

    $action = $_POST['action'] ?? '';

    // --- Mise a jour bio + experience ---
    if ($action === 'update_profil') {
        $bio        = trim($_POST['bio']        ?? '');
        $experience = trim($_POST['experience'] ?? '');

        if (empty($bio) || empty($experience)) {
            set_error('La bio et l experience sont obligatoires.');
            redirect_to('mentor-profil');
        }

        mettre_a_jour_profil_mentor($user_id, $bio, $experience);
        set_success('Profil mentor mis a jour.');
        redirect_to('mentor-profil');
    }

    // --- Mise a jour matieres enseignees ---
    if ($action === 'update_matieres') {
        $ids = $_POST['matieres'] ?? [];

        if (empty($ids)) {
            set_error('Selectionne au moins une matiere.');
            redirect_to('mentor-profil');
        }

        mettre_a_jour_matieres_mentor($user_id, $ids);
        set_success('Matieres mises a jour.');
        redirect_to('mentor-profil');
    }

    // --- Changement statut disponibilite ---
    if ($action === 'update_statut') {
        $statut = $_POST['statut_dispo'] ?? 'disponible';
        $statuts_ok = ['disponible', 'occupe', 'inactif'];

        if (!in_array($statut, $statuts_ok)) {
            set_error('Statut invalide.');
            redirect_to('mentor-profil');
        }

        changer_statut_dispo($user_id, $statut);
        set_success('Statut de disponibilite mis a jour.');
        redirect_to('mentor-profil');
    }
}

// Donnees pour la vue
$profil_mentor   = get_profil_mentor($user_id);
$toutes_matieres = get_matieres_par_categorie();
$matieres_mentor = get_matieres_mentor($user_id);
$ids_mentor      = array_column($matieres_mentor, 'id');
$page_active     = 'mentor-profil';

require_once BASE_PATH . '/views/mentor/profil_public.php';
