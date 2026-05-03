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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-slideInRight { animation: slideInRight 0.4s ease-out forwards; }
        
        /* ==================== STAT CARDS ==================== */
        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px 24px;
            transition: all 0.3s ease;
            border: 1px solid #E2E8F0;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0FC4A7, #0D9488);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        
        .stat-card:hover::before { transform: scaleX(1); }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #0F172A;
            margin-top: 12px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #64748B;
            font-weight: 500;
        }
        
        /* ==================== GRIDS ==================== */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        /* ==================== CARDS ==================== */
        .card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-link {
            font-size: 13px;
            color: #0FC4A7;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .card-link:hover { color: #0D9488; }
        
        /* ==================== DEMANDE ITEM ==================== */
        .demand-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 24px;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.2s;
        }
        
        .demand-item:hover {
            background: #F8FAFC;
        }
        
        .demand-avatar {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }
        
        .btn-view-demand {
            background: #0FC4A7;
            color: #fff;
            padding: 6px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-view-demand:hover {
            background: #0D9488;
            transform: translateY(-1px);
        }
        
        /* ==================== SESSION CARD ==================== */
        .session-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.2s;
        }
        
        .session-item:hover {
            background: #F8FAFC;
        }
        
        .session-date {
            background: #F8FAFC;
            border-radius: 16px;
            padding: 10px 16px;
            text-align: center;
            min-width: 80px;
        }
        
        .badge-success {
            background: #E8F5E9;
            color: #2E7D32;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .badge-warning {
            background: #FFF3E0;
            color: #E65100;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-add-slot {
            background: linear-gradient(135deg, #0FC4A7, #0D9488);
            color: #fff;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .btn-add-slot:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15,196,167,0.3);
        }
        
        /* ==================== STATUS SELECTOR ==================== */
        .status-selector {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 4px;
            display: inline-flex;
            gap: 8px;
        }
        
        .status-option {
            padding: 8px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: transparent;
            border: none;
        }
        
        .status-option.active {
            background: #0FC4A7;
            color: #fff;
        }
        
        .status-option[data-status="disponible"]:hover { background: #E8F5E9; color: #2E7D32; }
        .status-option[data-status="occupe"]:hover { background: #FFF3E0; color: #E65100; }
        .status-option[data-status="inactif"]:hover { background: #FFEBEE; color: #C62828; }
        
        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            background: #F1F5F9;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }
        
        @media (max-width: 768px) {
            .grid-4 { grid-template-columns: 1fr; }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main style="padding: 32px;">
    
    <!-- Welcome Section -->
    <div class="animate-fadeInUp" style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
            <i class="fa-solid fa-chalkboard-user" style="color: #0FC4A7; margin-right: 12px;"></i>
            Bonjour, <?= e($utilisateur['prenom'] ?? $_SESSION['nom'] ?? 'Mentor') ?> 
        </h1>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <p style="color: #64748B; font-size: 14px;">
                <i class="fa-regular fa-calendar"></i> Tableau de bord mentor · <?= date('d/m/Y') ?>
            </p>
            
            <!-- Statut dispo rapide -->
            <form method="POST" action="<?= APP_URL ?>/?url=mentor-profil" class="status-selector">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_statut">
                <button type="submit" name="statut_dispo" value="disponible" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'disponible' ? 'active' : '' ?>"
                        data-status="disponible">
                    <i class=""></i> Disponible
                </button>
                <button type="submit" name="statut_dispo" value="occupe" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'occupe' ? 'active' : '' ?>"
                        data-status="occupe">
                    <i class=""></i> Occupé
                </button>
                <button type="submit" name="statut_dispo" value="inactif" 
                        class="status-option <?= ($profil_mentor['statut_dispo'] ?? '') === 'inactif' ? 'active' : '' ?>"
                        data-status="inactif">
                    <i class=""></i> Inactif
                </button>
            </form>
        </div>
    </div>
    
    <!-- ==================== STATS CARDS ==================== -->
    <div class="grid-4 animate-fadeInUp" style="margin-bottom: 28px;">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Sessions réalisées</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_realisees'] ?? 0) ?></div>
            <div style="margin-top: 8px; font-size: 12px; color: #10B981;">
                <i class="fa-solid fa-chart-line"></i> +<?= $stats['sessions_realisees'] ?? 0 ?> au total
            </div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Demandes en attente</span>
                <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['demandes_en_attente'] ?? 0) ?></div>
            <?php if (($stats['demandes_en_attente'] ?? 0) > 0): ?>
            <div style="margin-top: 8px; font-size: 12px; color: #F59E0B;">
                <i class="fa-solid fa-bell"></i> À traiter rapidement
            </div>
            <?php endif; ?>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Sessions à venir</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_a_venir'] ?? 0) ?></div>
            <?php if (($stats['sessions_a_venir'] ?? 0) > 0): ?>
            <div style="margin-top: 8px; font-size: 12px; color: #0FC4A7;">
                <i class="fa-solid fa-hourglass-half"></i> Prochainement
            </div>
            <?php endif; ?>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Note moyenne</span>
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #EF4444;">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['note_moyenne'] ?? 0, 1) ?> <span style="font-size: 14px;">/5</span></div>
            <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
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
                <p style="color: #64748B; font-size: 14px; margin-bottom: 8px;">Aucune demande en attente</p>
                <p style="color: #94A3B8; font-size: 13px;">Vous êtes à jour !</p>
            </div>
            <?php else: ?>
            <div>
                <?php foreach (array_slice($demandes, 0, 4) as $d): ?>
                <div class="demand-item">
                    <div class="demand-avatar">
                        <?= strtoupper(substr($d['apprenant_nom_complet'], 0, 1)) ?>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-weight: 600; color: #0F172A; margin-bottom: 4px;">
                            <?= e($d['apprenant_nom_complet']) ?>
                        </p>
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-size: 12px; color: #0FC4A7; background: rgba(15,196,167,0.1); padding: 2px 8px; border-radius: 20px;">
                                <i class="fa-solid fa-book"></i> <?= e($d['matiere_nom']) ?>
                            </span>
                            <span style="font-size: 12px; color: #64748B;">
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
                <p style="color: #64748B; font-size: 14px; margin-bottom: 8px;">Aucune session à venir</p>
                <p style="color: #94A3B8; font-size: 13px; margin-bottom: 20px;">Créez des disponibilités pour recevoir des demandes</p>
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
                        <p style="font-size: 11px; font-weight: 600; color: #0FC4A7; text-transform: uppercase; margin-bottom: 4px;">
                            <?= strtoupper(date('M', strtotime($sess['date_session']))) ?>
                        </p>
                        <p style="font-size: 20px; font-weight: 800; color: #0F172A;">
                            <?= date('d', strtotime($sess['date_session'])) ?>
                        </p>
                    </div>
                    
                    <!-- Infos -->
                    <div style="flex: 1;">
                        <p style="font-weight: 600; color: #0F172A; margin-bottom: 4px;">
                            <?= e($sess['matiere_nom']) ?>
                            <span style="color: #94A3B8; font-weight: 400;">avec</span>
                            <?= e($sess['apprenant_nom_complet']) ?>
                        </p>
                        <p style="font-size: 12px; color: #64748B;">
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

</div><!-- ferme main-content-wrapper -->

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
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>