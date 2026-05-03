<?php
// ============================================================
//  controllers/etudiant/profil_controller.php
//  F06 — Modifier infos personnelles + photo
//  F07 — Gerer ses matieres
//  F08 — Changer mot de passe
// ============================================================

require_once BASE_PATH . '/models/user_model.php';
require_once BASE_PATH . '/models/matiere_model.php';

require_logged_in();

$user_id = $_SESSION['user_id'];

// ============================================================
//  Traitement POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify()) {
        set_error('Requete invalide.');
        redirect_to('profil');
    }

    $action = $_POST['action'] ?? '';

    // ----------------------------------------------------------
    //  F06 — Mise a jour infos personnelles
    // ----------------------------------------------------------
    if ($action === 'update_infos') {

        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        if (empty($nom) || empty($prenom)) {
            set_error('Le nom et le prenom sont obligatoires.');
            redirect_to('profil');
        }

        // Gestion upload photo
        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext_ok = ['jpg', 'jpeg', 'png', 'webp'];
            $ext    = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $taille = $_FILES['photo']['size'];

            if (!in_array($ext, $ext_ok)) {
                set_error('Format de photo invalide. Utilisez JPG, PNG ou WEBP.');
                redirect_to('profil');
            }
            if ($taille > 2 * 1024 * 1024) {
                set_error('La photo ne doit pas depasser 2 Mo.');
                redirect_to('profil');
            }

            $nom_fichier = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
            $destination = UPLOAD_DIR . 'avatars/' . $nom_fichier;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                set_error('Erreur lors de l upload de la photo.');
                redirect_to('profil');
            }
            $photo = $nom_fichier;
        }

        mettre_a_jour_profil($user_id, $nom, $prenom, $photo);
        $_SESSION['nom'] = $prenom . ' ' . $nom;

        set_success('Profil mis a jour avec succes.');
        redirect_to('profil');
    }

    // ----------------------------------------------------------
    //  F07 — Mise a jour des matieres
    // ----------------------------------------------------------
    if ($action === 'update_matieres') {
        $ids = $_POST['matieres'] ?? [];
        mettre_a_jour_matieres_etudiant($user_id, $ids);
        set_success('Matieres mises a jour.');
        redirect_to('profil');
    }

    // ----------------------------------------------------------
    //  F08 — Changement de mot de passe
    // ----------------------------------------------------------
    if ($action === 'update_mdp') {
        $ancien  = $_POST['ancien_mdp']  ?? '';
        $nouveau = $_POST['nouveau_mdp'] ?? '';
        $confirm = $_POST['confirm_mdp'] ?? '';

        $user = trouver_utilisateur_par_id($user_id);

        if (!password_verify($ancien, $user['mot_de_passe'])) {
            set_error('L ancien mot de passe est incorrect.');
            redirect_to('profil');
        }
        if (strlen($nouveau) < 8) {
            set_error('Le nouveau mot de passe doit contenir au moins 8 caracteres.');
            redirect_to('profil');
        }
        if ($nouveau !== $confirm) {
            set_error('Les mots de passe ne correspondent pas.');
            redirect_to('profil');
        }

        mettre_a_jour_mot_de_passe($user_id, $nouveau);
        set_success('Mot de passe modifie avec succes.');
        redirect_to('profil');
    }
}

// ============================================================
//  Donnees pour la vue
// ============================================================
$utilisateur       = trouver_utilisateur_par_id($user_id);
$toutes_matieres   = get_matieres_par_categorie();
$matieres_etudiant = get_matieres_etudiant($user_id);
$ids_etudiant      = array_column($matieres_etudiant, 'id');
$page_active       = 'profil';

require_once BASE_PATH . '/views/etudiant/profil.php';