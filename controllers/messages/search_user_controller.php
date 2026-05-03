<?php
// ============================================================
//  controllers/message/search_user_controller.php
//  Recherche live d'utilisateurs pour le modal "Nouveau message"
//  GET : ?url=search-user&q=texte
//  Réponse JSON
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

header('Content-Type: application/json; charset=utf-8');

$pdo     = get_pdo();
$user_id = getUserId();
$q       = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$search = '%' . $q . '%';

$stmt = $pdo->prepare("
    SELECT
        u.id,
        u.nom,
        u.prenom,
        u.photo,
        CONCAT(u.prenom, ' ', u.nom) AS nom_complet,
        u.est_mentor,
        u.mentor_valide,
        mp.note_moyenne
    FROM utilisateurs u
    LEFT JOIN mentors_profils mp ON mp.utilisateur_id = u.id
    WHERE u.id     != :uid
      AND u.statut  = 'actif'
      AND (u.nom LIKE :q OR u.prenom LIKE :q2
           OR CONCAT(u.prenom, ' ', u.nom) LIKE :q3)
    ORDER BY
        u.est_mentor DESC,
        mp.note_moyenne DESC,
        u.prenom ASC
    LIMIT 10
");
$stmt->execute([
    ':uid' => $user_id,
    ':q'   => $search,
    ':q2'  => $search,
    ':q3'  => $search,
]);
$users = $stmt->fetchAll();

$result = array_map(fn($u) => [
    'id'          => (int) $u['id'],
    'nom_complet' => $u['nom_complet'],
    'initiale'    => strtoupper(substr($u['prenom'], 0, 1)),
    'role'        => ($u['est_mentor'] && $u['mentor_valide']) ? 'Mentor' : 'Étudiant',
    'note'        => $u['note_moyenne'] > 0
                        ? number_format((float)$u['note_moyenne'], 1)
                        : null,
], $users);

echo json_encode($result);
exit;