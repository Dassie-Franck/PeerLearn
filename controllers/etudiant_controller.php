<?php
// ============================================================
//  etudiant_controller.php
//  Gere : dashboard etudiant, profil, demande mentor
//  Chemin : controllers/etudiant_controller.php
// ============================================================

require_once APP_ROOT . '/models/user_model.php';
require_once APP_ROOT . '/models/profil_mentor_model.php';
require_once APP_ROOT . '/models/matiere_model.php';
require_once APP_ROOT . '/models/session_model.php';
require_once APP_ROOT . '/models/notification_model.php';
require_once APP_ROOT . '/includes/auth_check.php';

// Seuls les etudiants et mentors peuvent acceder a cet espace
if ($_SESSION['role'] !== 'etudiant') {
    redirect('/?url=admin/dashboard');
}

$parts  = explode('/', trim($_GET['url'] ?? '', '/'));
$action = $parts[1] ?? 'dashboard';

switch ($action) {

    // ----------------------------------------------------------
    //  DASHBOARD
    // ----------------------------------------------------------
    case 'dashboard':
        $user_id = $_SESSION['user_id'];

        // Recupere les infos de l utilisateur
        $utilisateur = trouver_utilisateur_par_id($user_id);

        // Recupere les prochaines sessions a venir (confirmees)
        $sessions_a_venir = get_sessions_a_venir($user_id, 5);

        // Recupere les sessions en attente de confirmation
        $sessions_en_attente = get_sessions_en_attente_etudiant($user_id);

        // Recupere les mentors suggeres selon les matieres de l etudiant
        $matieres_etudiant = get_matieres_etudiant($user_id);
        $mentors_suggeres  = [];
        if (!empty($matieres_etudiant)) {
            $mentors_suggeres = get_mentors_valides($matieres_etudiant[0]['id'], null, null);
            $mentors_suggeres = array_slice($mentors_suggeres, 0, 4);
        }

        // Compteurs pour les stats du dashboard
        $stats = get_stats_etudiant($user_id);

        // Notifications non lues
        $nb_notifs      = compter_notifications_non_lues($user_id);
        $notifications = get_notifications($user_id, 5);

        require_once APP_ROOT . '/views/etudiant/dashboard.php';
        break;


    // ----------------------------------------------------------
    //  PROFIL — affichage et modification
    // ----------------------------------------------------------
    case 'profil':
        $user_id = $_SESSION['user_id'];

        $erreurs = $_SESSION['erreurs'] ?? []; unset($_SESSION['erreurs']);
        $succes  = $_SESSION['succes']  ?? null; unset($_SESSION['succes']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!csrf_verify()) {
                $_SESSION['erreur'] = "Requete invalide.";
                redirect('/?url=etudiant/profil');
            }

            $action_post = $_POST['action'] ?? '';

            // --- Mise a jour des infos personnelles ---
            if ($action_post === 'update_profil') {
                $nom    = trim($_POST['nom']    ?? '');
                $prenom = trim($_POST['prenom'] ?? '');

                $erreurs = [];
                if (empty($nom))    $erreurs[] = "Le nom est obligatoire.";
                if (empty($prenom)) $erreurs[] = "Le prenom est obligatoire.";

                // Gestion de l upload de photo
                $photo = null;
                if (!empty($_FILES['photo']['name'])) {
                    $ext_autorisees = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

                    if (!in_array($ext, $ext_autorisees)) {
                        $erreurs[] = "Format de photo non autorise (jpg, jpeg, png, webp).";
                    } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
                        $erreurs[] = "La photo ne doit pas depasser 2 Mo.";
                    } else {
                        $nom_fichier = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
                        $dest        = UPLOAD_DIR . 'avatars/' . $nom_fichier;
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                            $photo = $nom_fichier;
                        }
                    }
                }

                if (!empty($erreurs)) {
                    $_SESSION['erreurs'] = $erreurs;
                    redirect('/?url=etudiant/profil');
                }

                mettre_a_jour_profil($user_id, $nom, $prenom, $photo);

                // Met a jour le nom en session
                $_SESSION['nom'] = $prenom . ' ' . $nom;
                $_SESSION['succes'] = "Profil mis a jour avec succes.";
                redirect('/?url=etudiant/profil');
            }

            // --- Mise a jour des matieres ---
            if ($action_post === 'update_matieres') {
                $matiere_ids = $_POST['matieres'] ?? [];
                mettre_a_jour_matieres_etudiant($user_id, $matiere_ids);
                $_SESSION['succes'] = "Matieres mises a jour.";
                redirect('/?url=etudiant/profil');
            }

            // --- Demande d activation du profil mentor ---
            if ($action_post === 'demande_mentor') {
                $bio        = trim($_POST['bio']        ?? '');
                $experience = trim($_POST['experience'] ?? '');
                $matieres   = $_POST['matieres_mentor'] ?? [];

                $erreurs = [];
                if (empty($bio))        $erreurs[] = "La bio est obligatoire.";
                if (empty($experience)) $erreurs[] = "L experience est obligatoire.";
                if (empty($matieres))   $erreurs[] = "Selectionne au moins une matiere a enseigner.";

                if (!empty($erreurs)) {
                    $_SESSION['erreurs'] = $erreurs;
                    redirect('/?url=etudiant/profil');
                }

                demander_profil_mentor($user_id, $bio, $experience);
                mettre_a_jour_matieres_mentor($user_id, $matieres);

                $_SESSION['succes'] = "Ta demande de profil mentor a ete envoyee. L administrateur va l examiner.";
                $_SESSION['est_mentor'] = 1;
                redirect('/?url=etudiant/profil');
            }

            // --- Changement de mot de passe ---
            if ($action_post === 'update_mdp') {
                $ancien  = $_POST['ancien_mdp']  ?? '';
                $nouveau = $_POST['nouveau_mdp']  ?? '';
                $confirm = $_POST['confirm_mdp']  ?? '';

                $utilisateur = trouver_utilisateur_par_id($user_id);
                $erreurs = [];

                if (!password_verify($ancien, $utilisateur['mot_de_passe'])) {
                    $erreurs[] = "L ancien mot de passe est incorrect.";
                }
                if (strlen($nouveau) < 8) {
                    $erreurs[] = "Le nouveau mot de passe doit avoir au moins 8 caracteres.";
                }
                if ($nouveau !== $confirm) {
                    $erreurs[] = "Les mots de passe ne correspondent pas.";
                }

                if (!empty($erreurs)) {
                    $_SESSION['erreurs'] = $erreurs;
                    redirect('/?url=etudiant/profil');
                }

                mettre_a_jour_mot_de_passe($user_id, $nouveau);
                $_SESSION['succes'] = "Mot de passe modifie avec succes.";
                redirect('/?url=etudiant/profil');
            }
        }

        // Affichage du profil
        $utilisateur       = trouver_utilisateur_par_id($user_id);
        $toutes_matieres   = get_matieres_par_categorie();
        $matieres_etudiant = get_matieres_etudiant($user_id);
        $ids_etudiant      = array_column($matieres_etudiant, 'id');
        $profil_mentor     = null;
        $matieres_mentor   = [];
        $ids_mentor        = [];

        if ($utilisateur['est_mentor']) {
            $profil_mentor   = get_profil_mentor($user_id);
            $matieres_mentor = get_matieres_mentor($user_id);
            $ids_mentor      = array_column($matieres_mentor, 'id');
        }

        require_once APP_ROOT . '/views/etudiant/profil.php';
        break;


    // ----------------------------------------------------------
    //  Action inconnue
    // ----------------------------------------------------------
    default:
        require_once APP_ROOT . '/views/errors/404.php';
        break;
}