<?php
// ============================================================
//  controllers/home_controller.php
//  Page d'accueil publique
// ============================================================

// Pas besoin de auth_check.php car page publique

// Récupérer les statistiques réelles depuis la base de données
$stats = [];

try {
    $pdo = get_pdo();
    
    // Nombre total d'utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'etudiant'");
    $stats['etudiants'] = (int)$stmt->fetchColumn();
    
    // Nombre de mentors validés
    $stmt = $pdo->query("
        SELECT COUNT(*) FROM mentors_profils 
        WHERE statut_demande = 'validee'
    ");
    $stats['mentors'] = (int)$stmt->fetchColumn();
    
    // Nombre de sessions réalisées (terminées ou confirmées)
    $stmt = $pdo->query("
        SELECT COUNT(*) FROM sessions 
        WHERE statut IN ('terminee', 'confirmee')
    ");
    $stats['sessions'] = (int)$stmt->fetchColumn();
    
    // Nombre de matières disponibles
    $stmt = $pdo->query("SELECT COUNT(*) FROM matieres");
    $stats['matieres'] = (int)$stmt->fetchColumn();
    
    // Récupérer quelques avis récents (optionnel)
    $stmt = $pdo->query("
        SELECT e.*, u.prenom, u.nom, u.photo
        FROM evaluations e
        INNER JOIN utilisateurs u ON u.id = e.apprenant_id
        WHERE e.note >= 4
        ORDER BY e.created_at DESC
        LIMIT 6
    ");
    $avis_recents = $stmt->fetchAll();
    
} catch (Exception $e) {
    // Si la base n'est pas encore prête, mettre des valeurs par défaut
    $stats = [
        'etudiants' => 0,
        'mentors' => 0,
        'sessions' => 0,
        'matieres' => 0
    ];
    $avis_recents = [];
}

// Charger la vue
require_once BASE_PATH . '/views/home.php';
