<?php
// ============================================================
//  controllers/mentor/demandes_controller.php
//  Affiche les demandes de réservation reçues par le mentor
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireMentor();

$pdo       = get_pdo();
$mentor_id = getUserId();

// ── Récupère toutes les demandes reçues par ce mentor ──────
// Statuts affichés : en_attente, confirmee, annulee, refusee
$stmt = $pdo->prepare("
    SELECT
        s.id,
        s.date_session,
        s.heure_debut,
        s.heure_fin,
        s.statut,
        s.mode_session,
        s.lien_session,
        s.created_at,
        -- Apprenant
        u.nom           AS apprenant_nom,
        u.prenom        AS apprenant_prenom,
        u.photo         AS apprenant_photo,
        CONCAT(u.prenom, ' ', u.nom) AS apprenant_nom_complet,
        -- Matière
        m.nom           AS matiere_nom
    FROM sessions s
    JOIN utilisateurs u  ON u.id  = s.apprenant_id
    JOIN matieres     m  ON m.id  = s.matiere_id
    WHERE s.mentor_id = :mentor_id
    ORDER BY
        FIELD(s.statut, 'en_attente', 'confirmee', 'annulee', 'refusee'),
        s.date_session ASC,
        s.heure_debut  ASC
");
$stmt->execute([':mentor_id' => $mentor_id]);
$demandes = $stmt->fetchAll();

// ── Compteurs par statut (pour les badges dans l'en-tête) ──
$compteurs = [
    'en_attente' => 0,
    'confirmee'  => 0,
    'annulee'    => 0,
    'refusee'    => 0,
];
foreach ($demandes as $d) {
    if (isset($compteurs[$d['statut']])) {
        $compteurs[$d['statut']]++;
    }
}

$page_active = 'demandes';
require_once BASE_PATH . '/views/mentor/demandes.php';