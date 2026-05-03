<?php
// ============================================================
//  views/mentor/dashboard.php
//  Dashboard mentor - Design moderne
// ============================================================

$page_active = 'dashboard';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/mentor/dashboard.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main class="p-8">
    
    <!-- Welcome Section -->
    <div class="animate-fadeInUp mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            <i class="fa-solid fa-chalkboard-user" style="color: #0FC4A7; margin-right: 12px;"></i>
            Bonjour, <?= e($utilisateur['prenom'] ?? $_SESSION['nom'] ?? 'Mentor') ?> 
        </h1>
        <div class="flex items-center justify-between flex-wrap gap-4">
            <p class="text-gray-500 text-sm">
                <i class="fa-regular fa-calendar"></i> Tableau de bord mentor · <?= date('d/m/Y') ?>
            </p>
            
            <!-- Statut dispo rapide -->
            <form method="POST" action="<?= APP_URL ?>/?url=mentor-profil" class="status-selector">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_statut">
                <button type="submit" name="statut_dispo" value="disponible" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'disponible' ? 'active' : '' ?>"
                        data-status="disponible">
                    Disponible
                </button>
                <button type="submit" name="statut_dispo" value="occupe" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'occupe' ? 'active' : '' ?>"
                        data-status="occupe">
                    Occupé
                </button>
                <button type="submit" name="statut_dispo" value="inactif" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'inactif' ? 'active' : '' ?>"
                        data-status="inactif">
                    Inactif
                </button>
            </form>
        </div>
    </div>
    
    <!-- ==================== STATS CARDS ==================== -->
    <div class="grid-4 animate-fadeInUp mb-7">
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Sessions réalisées</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_realisees'] ?? 0) ?></div>
            <div class="mt-2 text-sm text-green-600">
                <i class="fa-solid fa-chart-line"></i> +<?= $stats['sessions_realisees'] ?? 0 ?> au total
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Demandes en attente</span>
                <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['demandes_en_attente'] ?? 0) ?></div>
            <?php if (($stats['demandes_en_attente'] ?? 0) > 0): ?>
            <div class="mt-2 text-sm text-amber-600">
                <i class="fa-solid fa-bell"></i> À traiter rapidement
            </div>
            <?php endif; ?>
        </div>
        
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Sessions à venir</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_a_venir'] ?? 0) ?></div>
            <?php if (($stats['sessions_a_venir'] ?? 0) > 0): ?>
            <div class="mt-2 text-sm text-teal-600">
                <i class="fa-solid fa-hourglass-half"></i> Prochainement
            </div>
            <?php endif; ?>
        </div>
        
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <span class="stat-label">Note moyenne</span>
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #EF4444;">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['note_moyenne'] ?? 0, 1) ?> <span class="text-sm">/5</span></div>
            <div class="mt-2 text-sm text-gray-500">
                <i class="fa-solid fa-users"></i> Basé sur <?= $stats['nb_evaluations'] ?? 0 ?> avis
            </div>
        </div>
    </div>
    
    <!-- ==================== DEMANDES & SESSIONS ==================== -->
    <div class="grid-2 animate-slideInRight">
        
        <!-- Demandes en attente -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-clock" style="color: #F59E0B;"></i>
                    Demandes en attente
                </h2>
                <a href="<?= APP_URL ?>/?url=demandes" class="card-link">
                    Voir tout <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            
            <?php if (empty($demandes) || count($demandes) == 0): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-regular fa-circle-check text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-sm mb-2">Aucune demande en attente</p>
                <p class="text-gray-400 text-sm">Vous êtes à jour !</p>
            </div>
            <?php else: ?>
            <div>
                <?php foreach (array_slice($demandes, 0, 4) as $d): ?>
                <div class="demand-item">
                    <div class="demand-avatar">
                        <?= strtoupper(substr($d['apprenant_nom_complet'], 0, 1)) ?>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 mb-1">
                            <?= e($d['apprenant_nom_complet']) ?>
                        </p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs text-teal-600 bg-teal-50 px-2 py-1 rounded-full">
                                <i class="fa-solid fa-book"></i> <?= e($d['matiere_nom']) ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($d['date_session'])) ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?= APP_URL ?>/?url=demandes" class="btn-view-demand">
                        <i class="fa-solid fa-eye"></i> Voir
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Prochaines sessions -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-calendar-days" style="color: #0FC4A7;"></i>
                    Prochaines sessions
                </h2>
                <a href="<?= APP_URL ?>/?url=mes-sessions" class="card-link">
                    Voir tout <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            
            <?php if (empty($sessions_a_venir)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-sm mb-2">Aucune session à venir</p>
                <p class="text-gray-400 text-sm mb-5">Créez des disponibilités pour recevoir des demandes</p>
                <a href="<?= APP_URL ?>/?url=disponibilites" class="btn-add-slot">
                    <i class="fa-solid fa-plus"></i> Ajouter des créneaux
                </a>
            </div>
            <?php else: ?>
            <div>
                <?php foreach ($sessions_a_venir as $sess): ?>
                <div class="session-item">
                    <!-- Date -->
                    <div class="session-date">
                        <p class="text-[11px] font-semibold text-teal-600 uppercase mb-1">
                            <?= strtoupper(date('M', strtotime($sess['date_session']))) ?>
                        </p>
                        <p class="text-xl font-extrabold text-gray-900">
                            <?= date('d', strtotime($sess['date_session'])) ?>
                        </p>
                    </div>
                    
                    <!-- Infos -->
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 mb-1">
                            <?= e($sess['matiere_nom']) ?>
                            <span class="text-gray-400 font-normal">avec</span>
                            <?= e($sess['apprenant_nom_complet']) ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fa-regular fa-clock"></i>
                            <?= date('H:i', strtotime($sess['heure_debut'])) ?> — <?= date('H:i', strtotime($sess['heure_fin'])) ?>
                        </p>
                    </div>
                    
                    <!-- Badge statut -->
                    <?php if ($sess['statut'] === 'confirmee'): ?>
                        <span class="badge-success">
                            <i class="fa-solid fa-check-circle"></i> Confirmée
                        </span>
                    <?php else: ?>
                        <span class="badge-warning">
                            <i class="fa-solid fa-hourglass-half"></i> En attente
                        </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
    </div>
    
</main>

<script>
// Animations au scroll
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.stat-card, .card').forEach(el => {
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>