<?php
// ============================================================
//  controllers/admin/toggle_user_controller.php
// ============================================================

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect_to('admin-users'); }
requireCsrf();

$pdo     = get_pdo();
$user_id = (int)($_POST['user_id'] ?? 0);
$action  = $_POST['action'] ?? '';

if ($user_id <= 0 || !in_array($action, ['suspendre', 'reactiver'])) {
    setToast('Action invalide.', 'error');
    redirect_to('admin-users');
}

if ($user_id === getUserId()) {
    setToast('Vous ne pouvez pas modifier votre propre compte.', 'error');
    redirect_to('admin-users');
}

$cible = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = :id LIMIT 1");
$cible->execute([':id' => $user_id]);
$cible = $cible->fetch();

if (!$cible) { setToast('Utilisateur introuvable.', 'error'); redirect_to('admin-users'); }
if ($cible['role'] === 'admin') { setToast('Impossible de modifier un administrateur.', 'error'); redirect_to('admin-users'); }

$nouveau_statut = $action === 'suspendre' ? 'suspendu' : 'actif';
$pdo->prepare("UPDATE utilisateurs SET statut = :statut WHERE id = :id")
    ->execute([':statut' => $nouveau_statut, ':id' => $user_id]);

// Journal — colonnes exactes de journaux_admin
$pdo->prepare("
    INSERT INTO journaux_admin (admin_id, action, description, ip_address, date_action)
    VALUES (:admin, :action, :desc, :ip, NOW())
")->execute([
    ':admin'  => getUserId(),
    ':action' => $action,
    ':desc'   => 'Utilisateur #' . $user_id . ' — statut changé en ' . $nouveau_statut,
    ':ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
]);

setToast($action === 'suspendre' ? 'Compte suspendu.' : 'Compte réactivé.', 'success');
redirect_to('admin-users');
