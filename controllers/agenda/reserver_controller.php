<?php
// ============================================================
//  controllers/agenda/reserver_controller.php
//  BF16, BF17 — Étudiant réserve un créneau
// ============================================================

require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/models/session_model.php';
require_once BASE_PATH . '/models/profil_mentor_model.php';
require_once BASE_PATH . '/models/notification_model.php';

require_logged_in();

$apprenant_id = getUserId();
$dispo_id     = (int)($_GET['dispo_id']  ?? 0);
$mentor_id    = (int)($_GET['mentor_id'] ?? 0);

// ── Validation des paramètres ────────────────────────────────
if ($dispo_id <= 0 || $mentor_id <= 0) {
    setToast('Créneau invalide.', 'error');
    redirect_to('recherche');
}

// ── Récupère le créneau ──────────────────────────────────────
$dispo = get_disponibilite_par_id($dispo_id, $mentor_id);

if (!$dispo || $dispo['est_reservee']) {
    setToast('Ce créneau n\'est plus disponible.', 'error');
    redirect_to('fiche-mentor&id=' . $mentor_id);
}

// ── Récupère le profil mentor ────────────────────────────────
$mentor = get_fiche_mentor($mentor_id);
if (!$mentor) {
    setToast('Mentor introuvable.', 'error');
    redirect_to('recherche');
}

// ── Traitement du formulaire POST ────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    // Re-vérifier que le créneau est encore libre (race condition)
    $dispo = get_disponibilite_par_id($dispo_id, $mentor_id);
    if (!$dispo || $dispo['est_reservee']) {
        setToast('Ce créneau vient d\'être réservé par quelqu\'un d\'autre.', 'error');
        redirect_to('fiche-mentor&id=' . $mentor_id);
    }

    $resultat = creer_session(
        $mentor_id,
        $apprenant_id,
        $dispo_id,
        (int)$dispo['matiere_id'],
        $dispo['date_dispo'],
        $dispo['heure_debut'],
        $dispo['heure_fin'],
        $dispo['mode_session']
    );

    if (isset($resultat['erreur'])) {
        setToast($resultat['erreur'], 'error');
        redirect_to('fiche-mentor&id=' . $mentor_id);
    }

    // Notifie le mentor
    $prenom_etudiant = $_SESSION['user_prenom'] ?? $_SESSION['prenom'] ?? 'Un étudiant';
    creer_notification(
        $mentor_id,
        'nouvelle_reservation',
        'Nouvelle demande de session',
        $prenom_etudiant . ' souhaite réserver un créneau avec vous.',
        '/?url=demandes'
    );

    setToast('Demande envoyée ! Le mentor va confirmer votre session.', 'success');
    redirect_to('mes-sessions');
}

$page_active = 'recherche';
require_once BASE_PATH . '/views/agenda/reserver.php';