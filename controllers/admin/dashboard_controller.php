<?php
// ============================================================
//  controllers/admin/dashboard_controller.php
//  Dashboard administrateur avec KPIs globaux
// ============================================================

require_once BASE_PATH . '/models/user_model.php';

require_logged_in();
require_admin();

$pdo = get_pdo();

// ── KPIs globaux ─────────────────────────────────────────────
$stats = $pdo->query("
    SELECT
        COUNT(*)                                   AS total_utilisateurs,
        SUM(role = 'etudiant')                     AS total_etudiants,
        SUM(est_mentor = 1 AND mentor_valide = 1)  AS total_mentors,
        SUM(est_mentor = 1 AND mentor_valide = 0)  AS mentors_en_attente,
        SUM(statut = 'suspendu')                   AS comptes_suspendus
    FROM utilisateurs
")->fetch();

$stats_sessions = $pdo->query("
    SELECT
        COUNT(*)                          AS total_sessions,
        SUM(statut = 'terminee')          AS sessions_terminees,
        SUM(statut = 'en_attente')        AS sessions_en_attente,
        SUM(statut = 'confirmee')         AS sessions_confirmees,
        SUM(statut = 'annulee')           AS sessions_annulees
    FROM sessions
")->fetch();

$stats_messages = $pdo->query("
    SELECT COUNT(*) AS total FROM messages
")->fetch();

$stats_evaluations = $pdo->query("
    SELECT COUNT(*) AS total, ROUND(AVG(note), 2) AS moyenne FROM evaluations
")->fetch();

$nb_signalements = (int)$pdo->query("
    SELECT COUNT(*) FROM signalements WHERE statut = 'en_attente'
")->fetchColumn();

// ── Derniers utilisateurs inscrits ───────────────────────────
$derniers_inscrits = $pdo->query("
    SELECT id, nom, prenom, email, role, est_mentor, mentor_valide, statut, created_at
    FROM utilisateurs
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll();

// ── Demandes mentor en attente ───────────────────────────────
$demandes_mentor = $pdo->query("
    SELECT u.id, u.nom, u.prenom, u.email, u.created_at,
           mp.bio, mp.experience
    FROM utilisateurs u
    INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
    WHERE u.est_mentor = 1 AND u.mentor_valide = 0 AND u.statut = 'actif'
    ORDER BY u.created_at ASC
    LIMIT 5
")->fetchAll();

// ── Sessions recentes ─────────────────────────────────────────
$sessions_recentes = $pdo->query("
    SELECT s.id, s.statut, s.date_session, s.mode_session,
           um.nom AS mentor_nom, um.prenom AS mentor_prenom,
           ua.nom AS apprenant_nom, ua.prenom AS apprenant_prenom,
           m.nom AS matiere_nom
    FROM sessions s
    INNER JOIN utilisateurs um ON um.id = s.mentor_id
    INNER JOIN utilisateurs ua ON ua.id = s.apprenant_id
    INNER JOIN matieres     m  ON m.id  = s.matiere_id
    ORDER BY s.created_at DESC
    LIMIT 5
")->fetchAll();

$page_active = 'admin';
require_once BASE_PATH . '/views/admin/dashboard.php';
