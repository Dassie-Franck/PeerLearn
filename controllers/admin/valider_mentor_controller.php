<?php
// ============================================================
//  controllers/admin/valider_mentor_controller.php
// ============================================================

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect_to('admin-users'); }
requireCsrf();

$pdo     = get_pdo();
$user_id = (int)($_POST['user_id'] ?? 0);

if ($user_id <= 0) { setToast('Utilisateur invalide.', 'error'); redirect_to('admin-users'); }

$stmt = $pdo->prepare("
    UPDATE utilisateurs
    SET mentor_valide = 1, statut = 'actif'
    WHERE id = :id AND est_mentor = 1 AND mentor_valide = 0
");
$stmt->execute([':id' => $user_id]);

if ($stmt->rowCount() === 0) {
    setToast('Demande introuvable ou déjà traitée.', 'error');
    redirect_to('admin-users');
}

// Notification
if (function_exists('creer_notification')) {
    creer_notification(
        $user_id, 'validation',
        'Votre profil mentor a été validé !',
        'Félicitations, vous pouvez maintenant proposer des sessions.',
        '/?url=mentor'
    );
}

// Journal
$pdo->prepare("
    INSERT INTO journaux_admin (admin_id, action, description, ip_address, date_action)
    VALUES (:admin, :action, :desc, :ip, NOW())
")->execute([
    ':admin'  => getUserId(),
    ':action' => 'valider_mentor',
    ':desc'   => 'Mentor #' . $user_id . ' validé',
    ':ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
]);

setToast('Mentor validé avec succès.', 'success');
redirect_to('admin-users&filtre=mentors_en_attente');