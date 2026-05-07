<?php
// ============================================================
//  views/mentor/dashboard.php
//  Dashboard mentor
// ============================================================

$page_active = 'dashboard';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor — <?= APP_NAME ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>

    <style>
        * { font-family: 'Inter', sans-serif; }

        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px 24px;
            border: 1px solid #E2E8F0;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .stat-value { font-size: 32px; font-weight: 800; color: #0F172A; margin-top: 12px; }
        .stat-label { font-size: 14px; color: #64748B; font-weight: 500; }
        .stat-icon { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; }

        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }

        .card { background: #fff; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .card-link { font-size: 13px; color: #0FC4A7; text-decoration: none; }

        .status-selector { background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 4px; display: inline-flex; gap: 8px; }
        .status-option { padding: 8px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; cursor: pointer; background: transparent; border: none; }
        .status-option.active { background: #0FC4A7; color: #fff; }

        .demand-item { display: flex; align-items: center; gap: 14px; padding: 16px 24px; border-bottom: 1px solid #F1F5F9; }
        .demand-avatar { width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #F59E0B, #D97706); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; }
        .btn-view-demand { background: #0FC4A7; color: #fff; padding: 6px 16px; border-radius: 10px; font-size: 12px; text-decoration: none; }

        .session-item { display: flex; align-items: center; gap: 16px; padding: 16px 24px; border-bottom: 1px solid #F1F5F9; }
        .session-date { background: #F8FAFC; border-radius: 16px; padding: 10px 16px; text-align: center; min-width: 80px; }
        .badge-success { background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .badge-warning { background: #FFF3E0; color: #E65100; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .btn-add-slot { background: linear-gradient(135deg, #0FC4A7, #0D9488); color: #fff; padding: 12px 24px; border-radius: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon { width: 80px; height: 80px; background: #F1F5F9; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }

        @media (max-width: 1024px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } .grid-2 { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .grid-4 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<main class="p-8">

    <div class="animate-fadeInUp mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            <i class="fa-solid fa-chalkboard-user" style="color: #0FC4A7;"></i>
            Bonjour, <?= e($utilisateur['prenom'] ?? 'Mentor') ?>
        </h1>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <p class="text-gray-500 text-sm">Tableau de bord mentor · <?= date('d/m/Y') ?></p>

            <form method="POST" action="<?= APP_URL ?>/mentor-profil" class="status-selector">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_statut">
                <button type="submit" name="statut_dispo" value="disponible"
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'disponible' ? 'active' : '' ?>">Disponible</button>
                <button type="submit" name="statut_dispo" value="occupe"
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'occupe' ? 'active' : '' ?>">Occupé</button>
                <button type="submit" name="statut_dispo" value="inactif"
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'inactif' ? 'active' : '' ?>">Inactif</button>
            </form>
        </div>
    </div>

    <div class="grid-4 mb-7">
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Sessions réalisées</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_realisees'] ?? 0) ?></div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Demandes en attente</span>
                <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;"><i class="fa-solid fa-clock"></i></div>
            </div>
            <div class="stat-value"><?= number_format($stats['demandes_en_attente'] ?? 0) ?></div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Sessions à venir</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;"><i class="fa-solid fa-calendar"></i></div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_a_venir'] ?? 0) ?></div>
        </div>
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Note moyenne</span>
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #EF4444;"><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="stat-value"><?= number_format($stats['note_moyenne'] ?? 0, 1) ?> <span class="text-sm">/5</span></div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-clock" style="color: #F59E0B;"></i> Demandes en attente</h2>
                <a href="<?= APP_URL ?>/demandes" class="card-link">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <?php if (empty($demandes)): ?>
            <div class="empty-state"><div class="empty-icon"><i class="fa-regular fa-circle-check text-3xl text-gray-400"></i></div><p>Aucune demande</p></div>
            <?php else: ?>
                <?php foreach (array_slice($demandes, 0, 4) as $d): ?>
                <div class="demand-item">
                    <div class="demand-avatar"><?= strtoupper(substr($d['apprenant_nom_complet'], 0, 1)) ?></div>
                    <div class="flex-1"><p class="font-semibold"><?= e($d['apprenant_nom_complet']) ?></p><span class="text-xs text-teal-600"><?= e($d['matiere_nom']) ?></span></div>
                    <a href="<?= APP_URL ?>/demandes" class="btn-view-demand">Voir</a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-calendar-days" style="color: #0FC4A7;"></i> Prochaines sessions</h2>
                <a href="<?= APP_URL ?>/mes-sessions" class="card-link">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <?php if (empty($sessions_a_venir)): ?>
            <div class="empty-state"><div class="empty-icon"><i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i></div><p>Aucune session</p><a href="<?= APP_URL ?>/disponibilites" class="btn-add-slot mt-4">Ajouter des créneaux</a></div>
            <?php else: ?>
                <?php foreach ($sessions_a_venir as $sess): ?>
                <div class="session-item">
                    <div class="session-date"><p class="text-teal-600 text-xs uppercase"><?= strtoupper(date('M', strtotime($sess['date_session']))) ?></p><p class="text-xl font-bold"><?= date('d', strtotime($sess['date_session'])) ?></p></div>
                    <div class="flex-1"><p class="font-semibold"><?= e($sess['matiere_nom']) ?> avec <?= e($sess['apprenant_nom_complet']) ?></p><p class="text-xs text-gray-500"><?= date('H:i', strtotime($sess['heure_debut'])) ?> — <?= date('H:i', strtotime($sess['heure_fin'])) ?></p></div>
                    <span class="badge-success">Confirmée</span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</main>

</div>

<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => { if (entry.isIntersecting) { entry.target.style.opacity = '1'; entry.target.style.transform = 'translateY(0)'; } });
}, { threshold: 0.1 });
document.querySelectorAll('.stat-card, .card').forEach(el => { el.style.opacity = '0'; el.style.transform = 'translateY(20px)'; el.style.transition = 'all 0.5s'; observer.observe(el); });
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>
