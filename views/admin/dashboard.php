<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — <?= APP_NAME ?></title>
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
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-slideInRight { animation: slideInRight 0.4s ease-out forwards; }
        
        /* ==================== LAYOUT ==================== */
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #F8FAFC;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
            color: #fff;
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 40;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-nav {
            padding: 20px 16px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin: 4px 0;
            border-radius: 12px;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            color: #fff;
            box-shadow: 0 4px 12px rgba(91,79,232,0.3);
        }
        
        .nav-item i { width: 24px; font-size: 18px; }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 24px 32px;
        }
        
        /* ==================== STAT CARDS ==================== */
        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 20px 24px;
            transition: all 0.3s ease;
            border: 1px solid #E2E8F0;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #5B4FE8, #0FC4A7);
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
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
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
            color: #5B4FE8;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .card-link:hover { color: #3B2BC8; }
        
        /* ==================== BADGES ==================== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            gap: 6px;
        }
        
        .badge-success { background: #E8F5E9; color: #2E7D32; }
        .badge-warning { background: #FFF3E0; color: #E65100; }
        .badge-error { background: #FFEBEE; color: #C62828; }
        .badge-info { background: #E3F2FD; color: #1565C0; }
        .badge-teal { background: #E0F2F1; color: #00695C; }
        .badge-gray { background: #F5F5F5; color: #616161; }
        
        /* ==================== TABLE ==================== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 20px;
            color: #64748B;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
        }
        
        .data-table td {
            padding: 14px 20px;
            color: #334155;
            font-size: 13px;
            border-bottom: 1px solid #F1F5F9;
        }
        
        .data-table tr {
            transition: background 0.2s;
        }
        
        .data-table tr:hover {
            background: #F8FAFC;
        }
        
        /* ==================== DEMANDE ITEM ==================== */
        .demand-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.2s;
        }
        
        .demand-item:hover {
            background: #F8FAFC;
        }
        
        .demand-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }
        
        .btn-validate {
            background: #10B981;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-validate:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        
        .btn-reject {
            background: #FEF2F2;
            color: #EF4444;
            border: 1px solid #FEE2E2;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-reject:hover {
            background: #FEE2E2;
            transform: translateY(-1px);
        }
        
        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1024px) {
            .sidebar { width: 80px; }
            .sidebar .logo-text, .sidebar .nav-item span { display: none; }
            .sidebar .nav-item { justify-content: center; }
            .sidebar .nav-item i { margin: 0; }
            .main-content { margin-left: 80px; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 16px; }
            .grid-4, .grid-2 { grid-template-columns: 1fr; }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>
<body>

<div class="admin-container">
    
    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="logo-text text-xl font-bold" style="background: linear-gradient(135deg, #fff, #94A3B8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PeerLearn</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 logo-text">Admin Dashboard</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?= APP_URL ?>/?url=admin" class="nav-item active">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="<?= APP_URL ?>/?url=admin-users" class="nav-item">
                <i class="fa-solid fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="<?= APP_URL ?>/?url=admin-sessions" class="nav-item">
                <i class="fa-solid fa-calendar"></i>
                <span>Sessions</span>
            </a>
            <a href="<?= APP_URL ?>/?url=admin-signalements" class="nav-item">
                <i class="fa-solid fa-flag"></i>
                <span>Signalements</span>
                <?php if ($nb_signalements > 0): ?>
                <span class="badge-error" style="background:#EF4444; color:white; font-size:10px; margin-left:auto; padding:2px 8px;"><?= $nb_signalements ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= APP_URL ?>/?url=admin-matières" class="nav-item">
                <i class="fa-solid fa-book"></i>
                <span>Matières</span>
            </a>
            <a href="<?= APP_URL ?>/?url=admin-settings" class="nav-item">
                <i class="fa-solid fa-gear"></i>
                <span>Paramètres</span>
            </a>
        </nav>
        
        <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 16px;">
            <a href="<?= APP_URL ?>/?url=logout" class="nav-item" style="color: #EF4444;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Déconnexion</span>
            </a>
        </div>
    </aside>
    
    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="main-content">
        
        <!-- Topbar mobile -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; background: #fff; padding: 16px 20px; border-radius: 16px; border: 1px solid #E2E8F0; display: none;" id="mobileTopbar">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition">
                <i class="fa-solid fa-bars text-gray-600 text-xl"></i>
            </button>
            <span class="font-bold text-gray-800">Admin Dashboard</span>
            <div style="width: 36px;"></div>
        </div>
        
        <!-- Welcome Section -->
        <div class="animate-fadeInUp" style="margin-bottom: 32px;">
            <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                <i class="fa-solid fa-gauge-high" style="color: #5B4FE8; margin-right: 12px;"></i>
                Tableau de bord
            </h1>
            <p style="color: #64748B; font-size: 14px;">
                Vue globale de la plateforme PeerLearn
                <?php if ($nb_signalements > 0): ?>
                <span class="badge-error" style="margin-left: 12px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <?= $nb_signalements ?> signalement<?= $nb_signalements > 1 ? 's' : '' ?> en attente
                </span>
                <?php endif; ?>
            </p>
        </div>
        
        <!-- ==================== STATS CARDS ==================== -->
        <div class="grid-4 animate-fadeInUp" style="margin-bottom: 28px;">
            <a href="<?= APP_URL ?>/?url=admin-users" class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Utilisateurs</span>
                    <div class="stat-icon" style="background: rgba(91,79,232,0.1); color: #5B4FE8;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats['total_utilisateurs'] ?? 0) ?></div>
                <div style="margin-top: 8px; font-size: 12px; color: #10B981;">
                    <i class="fa-solid fa-arrow-up"></i> +12% ce mois
                </div>
            </a>
            
            <a href="<?= APP_URL ?>/?url=admin-users&filtre=mentors" class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Mentors actifs</span>
                    <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats['total_mentors'] ?? 0) ?></div>
                <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                    <i class="fa-solid fa-check-circle"></i> Validés par admin
                </div>
            </a>
            
            <a href="<?= APP_URL ?>/?url=admin-users&filtre=mentors_en_attente" class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Demandes mentor</span>
                    <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats['mentors_en_attente'] ?? 0) ?></div>
                <?php if (($stats['mentors_en_attente'] ?? 0) > 0): ?>
                <div style="margin-top: 8px; font-size: 12px; color: #F59E0B;">
                    <i class="fa-solid fa-clock"></i> En attente de validation
                </div>
                <?php endif; ?>
            </a>
            
            <a href="<?= APP_URL ?>/?url=admin-users&filtre=suspendus" class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Comptes suspendus</span>
                    <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #EF4444;">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats['comptes_suspendus'] ?? 0) ?></div>
                <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                    <i class="fa-solid fa-shield"></i> À réviser
                </div>
            </a>
        </div>
        
        <!-- ==================== SECOND ROW STATS ==================== -->
        <div class="grid-4 animate-fadeInUp" style="margin-bottom: 28px;">
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Sessions totales</span>
                    <div class="stat-icon" style="background: rgba(91,79,232,0.1); color: #5B4FE8;">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats_sessions['total_sessions'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Sessions terminées</span>
                    <div class="stat-icon" style="background: rgba(34,197,94,0.1); color: #22C55E;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats_sessions['sessions_terminees'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Évaluations</span>
                    <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats_evaluations['total'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stat-label">Note moyenne</span>
                    <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value"><?= number_format($stats_evaluations['moyenne'] ?? 0, 1) ?> <span style="font-size: 14px;">/5</span></div>
            </div>
        </div>
        
        <!-- ==================== DEMANDES & DERNIERS INSCRITS ==================== -->
        <div class="grid-2 animate-fadeInUp" style="margin-bottom: 28px;">
            
            <!-- Demandes mentor en attente -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fa-solid fa-user-clock" style="color: #F59E0B;"></i>
                        Demandes mentor
                    </h2>
                    <a href="<?= APP_URL ?>/?url=admin-users&filtre=mentors_en_attente" class="card-link">
                        Voir tout <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <?php if (empty($demandes_mentor)): ?>
                <div style="padding: 40px 20px; text-align: center;">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-circle-check text-green-500 text-2xl"></i>
                    </div>
                    <p style="color: #64748B; font-size: 14px;">Aucune demande en attente</p>
                    <p style="color: #94A3B8; font-size: 12px; margin-top: 4px;">Toutes les demandes ont été traitées</p>
                </div>
                <?php else: ?>
                <div>
                    <?php foreach ($demandes_mentor as $d): ?>
                    <div class="demand-item">
                        <div class="demand-avatar" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            <?= strtoupper(substr($d['prenom'], 0, 1)) ?>
                        </div>
                        <div style="flex: 1;">
                            <p style="font-weight: 600; color: #0F172A; margin-bottom: 2px;"><?= e($d['prenom'] . ' ' . $d['nom']) ?></p>
                            <p style="font-size: 12px; color: #64748B;"><?= e($d['email']) ?></p>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <form method="POST" action="<?= APP_URL ?>/?url=admin-valider" style="display: inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $d['id'] ?>">
                                <button type="submit" class="btn-validate">
                                    <i class="fa-solid fa-check"></i> Valider
                                </button>
                            </form>
                            <form method="POST" action="<?= APP_URL ?>/?url=admin-rejeter" style="display: inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $d['id'] ?>">
                                <button type="submit" class="btn-reject">
                                    <i class="fa-solid fa-xmark"></i> Rejeter
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Derniers inscrits -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fa-solid fa-user-plus" style="color: #5B4FE8;"></i>
                        Derniers inscrits
                    </h2>
                    <a href="<?= APP_URL ?>/?url=admin-users" class="card-link">
                        Voir tout <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div>
                    <?php foreach ($derniers_inscrits as $u): ?>
                    <div class="demand-item">
                        <div class="demand-avatar" style="background: linear-gradient(135deg, #5B4FE8, #7C3AED);">
                            <?= strtoupper(substr($u['prenom'], 0, 1)) ?>
                        </div>
                        <div style="flex: 1;">
                            <p style="font-weight: 600; color: #0F172A; margin-bottom: 2px;"><?= e($u['prenom'] . ' ' . $u['nom']) ?></p>
                            <p style="font-size: 11px; color: #94A3B8;"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></p>
                        </div>
                        <?php if ($u['statut'] === 'suspendu'): ?>
                            <span class="badge-error" style="background: #FFEBEE; color: #C62828;">
                                <i class="fa-solid fa-ban"></i> Suspendu
                            </span>
                        <?php elseif ($u['est_mentor'] && $u['mentor_valide']): ?>
                            <span class="badge-teal">
                                <i class="fa-solid fa-graduation-cap"></i> Mentor
                            </span>
                        <?php elseif ($u['est_mentor']): ?>
                            <span class="badge-warning">
                                <i class="fa-solid fa-clock"></i> En attente
                            </span>
                        <?php else: ?>
                            <span class="badge-gray">
                                <i class="fa-solid fa-user"></i> Étudiant
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- ==================== SESSIONS RÉCENTES ==================== -->
        <div class="card animate-slideInRight">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-calendar-days" style="color: #5B4FE8;"></i>
                    Sessions récentes
                </h2>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mentor</th>
                            <th>Étudiant</th>
                            <th>Matière</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions_recentes as $s):
                            $badge = match($s['statut']) {
                                'terminee'   => '<span class="badge-success"><i class="fa-solid fa-circle-check"></i> Terminée</span>',
                                'confirmee'  => '<span class="badge-info"><i class="fa-solid fa-calendar-check"></i> Confirmée</span>',
                                'en_attente' => '<span class="badge-warning"><i class="fa-solid fa-clock"></i> En attente</span>',
                                'annulee'    => '<span class="badge-error"><i class="fa-solid fa-ban"></i> Annulée</span>',
                                default      => '<span class="badge-gray">' . $s['statut'] . '</span>',
                            };
                        ?>
                        <tr>
                            <td><strong><?= e($s['mentor_prenom'] . ' ' . $s['mentor_nom']) ?></strong></td>
                            <td><?= e($s['apprenant_prenom'] . ' ' . $s['apprenant_nom']) ?></td>
                            <td><?= e($s['matiere_nom']) ?></td>
                            <td><?= date('d/m/Y', strtotime($s['date_session'])) ?></td>
                            <td><?= $badge ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('mobile-open');
}

// Détecter les écrans mobiles pour afficher le topbar
function checkMobile() {
    const topbar = document.getElementById('mobileTopbar');
    if (window.innerWidth <= 768) {
        topbar.style.display = 'flex';
    } else {
        topbar.style.display = 'none';
    }
}

window.addEventListener('resize', checkMobile);
window.addEventListener('load', checkMobile);

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