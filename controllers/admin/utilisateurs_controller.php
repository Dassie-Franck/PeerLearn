<?php
// ============================================================
//  controllers/admin/utilisateurs_controller.php
//  Liste + filtres des utilisateurs
// ============================================================

require_logged_in();
require_admin();

$pdo    = get_pdo();
$filtre = $_GET['filtre'] ?? 'tous';
$search = trim($_GET['search'] ?? '');

$where  = [];
$params = [];

// Filtres
switch ($filtre) {
    case 'etudiants':
        $where[] = "u.est_mentor = 0 AND u.role != 'admin'";
        break;
    case 'mentors':
        $where[] = "u.est_mentor = 1 AND u.mentor_valide = 1";
        break;
    case 'mentors_en_attente':
        $where[] = "u.est_mentor = 1 AND u.mentor_valide = 0";
        break;
    case 'suspendus':
        $where[] = "u.statut = 'suspendu'";
        break;
    case 'admins':
        $where[] = "u.role = 'admin'";
        break;
}

// Recherche
if ($search !== '') {
    $where[] = "(u.nom LIKE :search OR u.prenom LIKE :search2 OR u.email LIKE :search3)";
    $params[':search']  = '%' . $search . '%';
    $params[':search2'] = '%' . $search . '%';
    $params[':search3'] = '%' . $search . '%';
}

$sql = "
    SELECT u.id, u.nom, u.prenom, u.email, u.role,
           u.est_mentor, u.mentor_valide, u.statut, u.created_at,
           (SELECT COUNT(*) FROM sessions s
            WHERE s.mentor_id = u.id OR s.apprenant_id = u.id) AS nb_sessions
    FROM utilisateurs u
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY u.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$utilisateurs = $stmt->fetchAll();

// Compteurs pour les onglets
$compteurs = $pdo->query("
    SELECT
        COUNT(*)                                                AS tous,
        SUM(est_mentor = 0 AND role != 'admin')                AS etudiants,
        SUM(est_mentor = 1 AND mentor_valide = 1)              AS mentors,
        SUM(est_mentor = 1 AND mentor_valide = 0)              AS mentors_en_attente,
        SUM(statut = 'suspendu')                               AS suspendus
    FROM utilisateurs
")->fetch();

$page_active = 'admin-users';
require_once BASE_PATH . '/views/admin/utilisateurs.php';