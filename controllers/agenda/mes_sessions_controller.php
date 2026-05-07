<?php
// ============================================================
//  controllers/agenda/mes_sessions_controller.php
//  Sessions de l'utilisateur connecté (étudiant + mentor)
// ============================================================
require_once BASE_PATH . '/config/session.php';
require_once BASE_PATH . '/includes/helpers.php';
require_once BASE_PATH . '/models/session_model.php';
require_once BASE_PATH . '/models/evaluation_model.php';

require_logged_in();

$user_id  = getUserId();
$est_mentor = estMentorValide();

// Mise à jour auto des sessions terminées
marquer_sessions_terminees();

// Toutes les sessions de l'utilisateur
$sessions = get_sessions_utilisateur($user_id);

// Récupérer les IDs des sessions déjà évaluées par cet apprenant
$deja_evalues = [];
if (!$est_mentor) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT session_id FROM evaluations WHERE apprenant_id = :uid
    ");
    $stmt->execute([':uid' => $user_id]);
    $deja_evalues = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// AJOUTER L'INFORMATION "déjà évalué" à chaque session
foreach ($sessions as &$s) {
    $s['deja_evalue'] = in_array($s['id'], $deja_evalues);
}

// Filtrer par statut si demandé
$statut_filtre = $_GET['statut'] ?? '';
if ($statut_filtre) {
    $sessions = array_filter($sessions, fn($s) => $s['statut'] === $statut_filtre);
}

$page_active = 'sessions';
require_once BASE_PATH . '/views/agenda/mes_sessions.php';
