<?php
// ============================================================
//  agenda_controller.php
//  Gere : disponibilites, reservations, statuts sessions
//  Chemin : controllers/agenda_controller.php
// ============================================================

require_once APP_ROOT . '/models/disponibilite_model.php';
require_once APP_ROOT . '/models/session_model.php';
require_once APP_ROOT . '/models/user_model.php';
require_once APP_ROOT . '/models/matiere_model.php';
require_once APP_ROOT . '/models/notification_model.php';
require_once APP_ROOT . '/includes/auth_check.php';

$parts  = explode('/', trim($_GET['url'] ?? '', '/'));
$action = $parts[1] ?? 'mes-sessions';

switch ($action) {

    // ----------------------------------------------------------
    //  MES SESSIONS — vue etudiant et mentor
    // ----------------------------------------------------------
    case 'mes-sessions':
        marquer_sessions_terminees(); // mise a jour auto des statuts

        $user_id = $_SESSION['user_id'];
        $filtre  = $_GET['statut'] ?? null;

        $sessions    = get_sessions_utilisateur($user_id, $filtre ?: null);
        $succes      = $_SESSION['succes'] ?? null; unset($_SESSION['succes']);
        $erreur      = $_SESSION['erreur'] ?? null; unset($_SESSION['erreur']);

        require_once APP_ROOT . '/views/etudiant/mes_sessions.php';
        break;


    // ----------------------------------------------------------
    //  RESERVER un creneau
    // ----------------------------------------------------------
    case 'reserver':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/?url=recherche');
        }

        if (!csrf_verify()) {
            $_SESSION['erreur'] = "Requete invalide.";
            redirect('/?url=recherche');
        }

        $apprenant_id    = $_SESSION['user_id'];
        $disponibilite_id = (int)($_POST['disponibilite_id'] ?? 0);

        // Recupere le creneau
        $dispo = get_disponibilite_by_id($disponibilite_id);
        if (!$dispo || $dispo['est_reservee']) {
            $_SESSION['erreur'] = "Ce creneau n est plus disponible.";
            redirect('/?url=recherche');
        }

        $mentor_id = $dispo['mentor_id'];

        // Regle metier : un etudiant ne peut pas reserver avec lui-meme
        if ($mentor_id == $apprenant_id) {
            $_SESSION['erreur'] = "Vous ne pouvez pas reserver une session avec vous-meme.";
            redirect('/?url=etudiant/fiche-mentor&id=' . $mentor_id);
        }

        // Cree la session
        $resultat = creer_session(
            $mentor_id,
            $apprenant_id,
            $disponibilite_id,
            $dispo['matiere_id'],
            $dispo['date_dispo'],
            $dispo['heure_debut'],
            $dispo['heure_fin'],
            $dispo['mode_session']
        );

        if (isset($resultat['erreur'])) {
            $_SESSION['erreur'] = $resultat['erreur'];
            redirect('/?url=etudiant/fiche-mentor&id=' . $mentor_id);
        }

        // Notifie le mentor
        $apprenant = trouver_utilisateur_par_id($apprenant_id);
        creer_notification(
            $mentor_id,
            'nouvelle_reservation',
            'Nouvelle demande de session',
            $apprenant['prenom'] . ' ' . $apprenant['nom'] .
            ' souhaite une session de ' . $dispo['matiere_nom'] .
            ' le ' . date('d/m/Y', strtotime($dispo['date_dispo'])),
            '/?url=mentor/demandes'
        );

        $_SESSION['succes'] = "Demande envoyee ! Le mentor va confirmer ta session.";
        redirect('/?url=agenda/mes-sessions');
        break;


    // ----------------------------------------------------------
    //  CONFIRMER une session (mentor)
    // ----------------------------------------------------------
    case 'confirmer':
        if (!csrf_verify()) {
            $_SESSION['erreur'] = "Requete invalide.";
            redirect('/?url=mentor/demandes');
        }

        require_role('etudiant'); // mentors ont aussi le role etudiant

        $session_id  = (int)($_POST['session_id']  ?? 0);
        $lien        = trim($_POST['lien_session'] ?? '');
        $mentor_id   = $_SESSION['user_id'];

        // Valide le lien pour sessions en ligne
        $session = get_session_par_id($session_id);
        if (!$session) {
            $_SESSION['erreur'] = "Session introuvable.";
            redirect('/?url=mentor/demandes');
        }

        if ($session['mode_session'] === 'en_ligne' && empty($lien)) {
            $_SESSION['erreur'] = "Un lien de visioconference est obligatoire pour une session en ligne.";
            redirect('/?url=mentor/demandes');
        }

        confirmer_session($session_id, $mentor_id, $lien ?: null);

        // Notifie l apprenant
        creer_notification(
            $session['apprenant_id'],
            'reservation_confirmee',
            'Session confirmee !',
            'Votre session de ' . $session['matiere_nom'] .
            ' le ' . date('d/m/Y', strtotime($session['date_session'])) . ' est confirmee.',
            '/?url=agenda/mes-sessions'
        );

        $_SESSION['succes'] = "Session confirmee avec succes.";
        redirect('/?url=mentor/demandes');
        break;


    // ----------------------------------------------------------
    //  ANNULER une session
    // ----------------------------------------------------------
    case 'annuler':
        if (!csrf_verify()) {
            $_SESSION['erreur'] = "Requete invalide.";
            redirect('/?url=agenda/mes-sessions');
        }

        $session_id = (int)($_POST['session_id'] ?? 0);
        $user_id    = $_SESSION['user_id'];
        $motif      = trim($_POST['motif'] ?? '');

        $session = get_session_par_id($session_id);
        if (!$session) {
            $_SESSION['erreur'] = "Session introuvable.";
            redirect('/?url=agenda/mes-sessions');
        }

        $ok = annuler_session($session_id, $user_id, $motif);

        if (!$ok) {
            $_SESSION['erreur'] = "Impossible d annuler cette session.";
            redirect('/?url=agenda/mes-sessions');
        }

        // Notifie l autre partie
        $destinataire = ($session['mentor_id'] == $user_id)
            ? $session['apprenant_id']
            : $session['mentor_id'];

        $annuleur = trouver_utilisateur_par_id($user_id);
        creer_notification(
            $destinataire,
            'reservation_annulee',
            'Session annulee',
            $annuleur['prenom'] . ' ' . $annuleur['nom'] .
            ' a annule la session du ' .
            date('d/m/Y', strtotime($session['date_session'])),
            '/?url=agenda/mes-sessions'
        );

        $_SESSION['succes'] = "Session annulee.";
        redirect('/?url=agenda/mes-sessions');
        break;


    // ----------------------------------------------------------
    //  DISPONIBILITES — espace mentor
    // ----------------------------------------------------------
    case 'disponibilites':
        if (!$_SESSION['est_mentor'] || !$_SESSION['mentor_valide']) {
            $_SESSION['erreur'] = "Acces reserve aux mentors valides.";
            redirect('/?url=etudiant/dashboard');
        }

        $mentor_id = $_SESSION['user_id'];
        $erreur    = $_SESSION['erreur'] ?? null; unset($_SESSION['erreur']);
        $succes    = $_SESSION['succes'] ?? null; unset($_SESSION['succes']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                $_SESSION['erreur'] = "Requete invalide.";
                redirect('/?url=agenda/disponibilites');
            }

            $action_post = $_POST['action'] ?? '';

            // --- Ajouter un creneau ---
            if ($action_post === 'ajouter') {
                $matiere_id  = (int)($_POST['matiere_id']  ?? 0);
                $date_dispo  = $_POST['date_dispo']  ?? '';
                $heure_debut = $_POST['heure_debut'] ?? '';
                $heure_fin   = $_POST['heure_fin']   ?? '';
                $mode        = $_POST['mode_session'] ?? 'en_ligne';

                $erreurs = [];
                if (!$matiere_id)   $erreurs[] = "Selectionne une matiere.";
                if (!$date_dispo)   $erreurs[] = "La date est obligatoire.";
                if (!$heure_debut)  $erreurs[] = "L heure de debut est obligatoire.";
                if (!$heure_fin)    $erreurs[] = "L heure de fin est obligatoire.";
                if ($heure_fin <= $heure_debut) $erreurs[] = "L heure de fin doit etre apres l heure de debut.";
                if ($date_dispo < date('Y-m-d')) $erreurs[] = "La date ne peut pas etre dans le passe.";

                if (!empty($erreurs)) {
                    $_SESSION['erreurs'] = $erreurs;
                    redirect('/?url=agenda/disponibilites');
                }

                $res = creer_disponibilite(
                    $mentor_id, $matiere_id, $date_dispo,
                    $heure_debut, $heure_fin, $mode
                );

                if (isset($res['erreur'])) {
                    $_SESSION['erreur'] = $res['erreur'];
                } else {
                    $_SESSION['succes'] = "Creneau ajoute avec succes.";
                }
                redirect('/?url=agenda/disponibilites');
            }

            // --- Supprimer un creneau ---
            if ($action_post === 'supprimer') {
                $dispo_id = (int)($_POST['dispo_id'] ?? 0);
                $ok = supprimer_disponibilite($dispo_id, $mentor_id);
                $_SESSION[$ok ? 'succes' : 'erreur'] = $ok
                    ? "Creneau supprime."
                    : "Impossible de supprimer ce creneau (deja reserve).";
                redirect('/?url=agenda/disponibilites');
            }
        }

        $disponibilites  = get_disponibilites_mentor($mentor_id);
        $matieres_mentor = get_matieres_mentor($mentor_id);
        $calendar_events = json_encode(get_disponibilites_calendar($mentor_id));
        $erreurs         = $_SESSION['erreurs'] ?? []; unset($_SESSION['erreurs']);

        require_once APP_ROOT . '/views/mentor/disponibilites.php';
        break;


    // ----------------------------------------------------------
    //  Action inconnue
    // ----------------------------------------------------------
    default:
        require_once APP_ROOT . '/views/errors/404.php';
        break;
}