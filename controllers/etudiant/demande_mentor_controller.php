<?php
// ============================================================
//  controllers/etudiant/demande_mentor_controller.php
//  F09 — Demande d activation profil mentor
// ============================================================

require_once BASE_PATH . '/models/user_model.php';
require_once BASE_PATH . '/models/matiere_model.php';

require_logged_in();

$user_id = $_SESSION['user_id'];

// Si deja mentor valide, redirige
if (is_mentor()) {
    redirect_to('mentor');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error('Requete invalide.');
        redirect_to('demande-mentor');
    }

    $bio        = trim($_POST['bio']        ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $ids        = $_POST['matieres']        ?? [];

    $erreurs = [];
    if (empty($bio))        $erreurs[] = 'La bio est obligatoire.';
    if (empty($experience)) $erreurs[] = 'L experience est obligatoire.';
    if (empty($ids))        $erreurs[] = 'Selectionne au moins une matiere a enseigner.';

    if (!empty($erreurs)) {
        set_error(implode(' ', $erreurs));
        redirect_to('demande-mentor');
    }

    demander_profil_mentor($user_id, $bio, $experience);
    mettre_a_jour_matieres_mentor($user_id, $ids);

    $_SESSION['est_mentor'] = 1;

    set_success('Demande envoyee ! L administrateur va l examiner sous peu.');
    redirect_to('dashboard');
}

// Donnees pour la vue
$toutes_matieres = get_matieres_par_categorie();
$page_active     = 'profil';

require_once BASE_PATH . '/views/etudiant/demande_mentor.php';
