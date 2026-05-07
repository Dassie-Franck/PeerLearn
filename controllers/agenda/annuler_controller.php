<?php
// ============================================================
//  controllers/agenda/annuler_controller.php
//  Annulation d'une session par l'étudiant ou le mentor
// ============================================================

require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/models/session_model.php';
require_once BASE_PATH . '/models/user_model.php';
require_once BASE_PATH . '/models/notification_model.php';

// Définir la fonction redirect si elle n'existe pas
if (!function_exists('redirect')) {
    function redirect($url) {
        global $APP_URL;
        if (strpos($url, 'http') !== 0 && strpos($url, '/') !== 0) {
            $url = APP_URL . '/?url=' . ltrim($url, '/');
        }
        header('Location: ' . $url);
        exit;
    }
}

// Définir la fonction getUserId si elle n'existe pas
if (!function_exists('getUserId')) {
    function getUserId() {
        return $_SESSION['user_id'] ?? 0;
    }
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    redirect('login');
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('mes-sessions');
    exit;
}

$session_id = (int)($_POST['session_id'] ?? 0);
$motif = trim($_POST['motif'] ?? '');

if (!$session_id) {
    $_SESSION['erreur'] = "Session invalide.";
    redirect('mes-sessions');
    exit;
}

// Récupérer les infos de la session
$session = get_session_par_id($session_id);

if (!$session) {
    $_SESSION['erreur'] = "Session introuvable.";
    redirect('mes-sessions');
    exit;
}

// Vérifier que l'utilisateur est bien concerné par cette session
if ($session['mentor_id'] != $user_id && $session['apprenant_id'] != $user_id) {
    $_SESSION['erreur'] = "Vous n'êtes pas autorisé à annuler cette session.";
    redirect('mes-sessions');
    exit;
}

// Vérifier que la session peut être annulée (pas déjà terminée ou annulée)
if (!in_array($session['statut'], ['en_attente', 'confirmee'])) {
    $_SESSION['erreur'] = "Cette session ne peut plus être annulée.";
    redirect('mes-sessions');
    exit;
}

// Annuler la session
$success = annuler_session($session_id, $user_id, $motif);

if ($success) {
    // Notifier l'autre participant
    $autre_id = ($user_id == $session['mentor_id']) ? $session['apprenant_id'] : $session['mentor_id'];

    // Récupérer les infos de l'utilisateur
    $user_info = trouver_utilisateur_par_id($user_id);
    $nom_utilisateur = ($user_info['prenom'] ?? 'Un utilisateur') . ' ' . ($user_info['nom'] ?? '');

    // Vérifier que la fonction creer_notification existe
    if (function_exists('creer_notification')) {
        creer_notification(
            $autre_id,
            'session_annulee',
            '❌ Session annulée',
            $nom_utilisateur . ' a annulé la session du ' . date('d/m/Y', strtotime($session['date_session'])) . '.',
            '/?url=mes-sessions'
        );
    }

    $_SESSION['succes'] = "La session a été annulée avec succès.";
} else {
    $_SESSION['erreur'] = "Une erreur est survenue lors de l'annulation.";
}

redirect('mes-sessions');
exit;
