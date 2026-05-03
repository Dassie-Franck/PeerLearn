<?php
// ============================================================
//  controllers/admin/signalements_controller.php
// ============================================================

requireAdmin();

$pdo    = get_pdo();
$filtre = $_GET['filtre'] ?? 'en_attente';
$filtre = in_array($filtre, ['en_attente','traite','rejete','tous']) ? $filtre : 'en_attente';

// ── Action POST (traiter / rejeter) ──────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $sig_id = (int)($_POST['sig_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($sig_id > 0 && in_array($action, ['traiter', 'rejeter'])) {
        $nouveau = $action === 'traiter' ? 'traite' : 'rejete';

        $pdo->prepare("
            UPDATE signalements
            SET statut = :statut, traite_par = :admin
            WHERE id = :id AND statut = 'en_attente'
        ")->execute([
            ':statut' => $nouveau,
            ':admin'  => getUserId(),
            ':id'     => $sig_id,
        ]);

        // Si traitement d'un avis → masquer l'évaluation signalée
        if ($action === 'traiter') {
            $sig = $pdo->prepare("SELECT type_cible, cible_id FROM signalements WHERE id = :id");
            $sig->execute([':id' => $sig_id]);
            $sig = $sig->fetch();
            if ($sig && $sig['type_cible'] === 'evaluation') {
                $pdo->prepare("UPDATE evaluations SET visible = 0 WHERE id = :id")
                    ->execute([':id' => $sig['cible_id']]);
            }
        }

        // Journal
        $pdo->prepare("
            INSERT INTO journaux_admin (admin_id, action, description, ip_address, date_action)
            VALUES (:admin, :action, :desc, :ip, NOW())
        ")->execute([
            ':admin'  => getUserId(),
            ':action' => $action . '_signalement',
            ':desc'   => 'Signalement #' . $sig_id . ' marqué ' . $nouveau,
            ':ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        setToast($action === 'traiter' ? 'Signalement traité.' : 'Signalement rejeté.', 'success');
    }

    redirect_to('admin-signalements&filtre=' . $filtre);
}

// ── Requête liste ─────────────────────────────────────────────
$where  = $filtre !== 'tous' ? "WHERE s.statut = :statut" : "";
$params = $filtre !== 'tous' ? [':statut' => $filtre] : [];

$stmt = $pdo->prepare("
    SELECT
        s.id, s.type_cible, s.cible_id, s.motif,
        s.statut, s.created_at,
        u.nom       AS signale_par_nom,
        u.prenom    AS signale_par_prenom,
        u.email     AS signale_par_email,
        t.nom       AS traite_par_nom,
        t.prenom    AS traite_par_prenom
    FROM signalements s
    INNER JOIN utilisateurs u ON u.id = s.signale_par
    LEFT  JOIN utilisateurs t ON t.id = s.traite_par
    $where
    ORDER BY s.created_at DESC
");
$stmt->execute($params);
$signalements = $stmt->fetchAll();

// Compteurs onglets
$compteurs = $pdo->query("
    SELECT
        COUNT(*)                          AS tous,
        SUM(statut = 'en_attente')        AS en_attente,
        SUM(statut = 'traite')            AS traite,
        SUM(statut = 'rejete')            AS rejete
    FROM signalements
")->fetch();

$page_active = 'admin-signalements';
require_once BASE_PATH . '/views/admin/signalements.php';