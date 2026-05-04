<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/admin/dashboard.css">
    
    <!-- Font Awesome (CDN - pas d'alternative locale simple) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
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
            <a href="<?= APP_URL ?>/admin" class="nav-item active">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="<?= APP_URL ?>/admin-users" class="nav-item">
                <i class="fa-solid fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="<?= APP_URL ?>/admin-sessions" class="nav-item">
                <i class="fa-solid fa-calendar"></i>
                <span>Sessions</span>
            </a>
            <a href="<?= APP_URL ?>/admin-signalements" class="nav-item">
                <i class="fa-solid fa-flag"></i>
                <span>Signalements</span>
                <?php if ($nb_signalements > 0): ?>
                <span class="badge-error" style="background:#EF4444; color:white; font-size:10px; margin-left:auto; padding:2px 8px;"><?= $nb_signalements ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= APP_URL ?>/admin-matières" class="nav-item">
                <i class="fa-solid fa-book"></i>
                <span>Matières</span>
            </a>
            <a href="<?= APP_URL ?>/admin-settings" class="nav-item">
                <i class="fa-solid fa-gear"></i>
                <span>Paramètres</span>
            </a>
        </nav>
        
        <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 16px;">
            <a href="<?= APP_URL ?>/logout" class="nav-item" style="color: #EF4444;">
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
            <a href="<?= APP_URL ?>/admin-users" class="stat-card">
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
            
            <a href="<?= APP_URL ?>/admin-users&filtre=mentors" class="stat-card">
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
            
            <a href="<?= APP_URL ?>/admin-users&filtre=mentors_en_attente" class="stat-card">
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
            
            <a href="<?= APP_URL ?>/admin-users&filtre=suspendus" class="stat-card">
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
                    <a href="<?= APP_URL ?>/admin-users&filtre=mentors_en_attente" class="card-link">
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
                            <form method="POST" action="<?= APP_URL ?>/admin-valider" style="display: inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $d['id'] ?>">
                                <button type="submit" class="btn-validate">
                                    <i class="fa-solid fa-check"></i> Valider
                                </button>
                            </form>
                            <form method="POST" action="<?= APP_URL ?>/admin-rejeter" style="display: inline;">
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
                    <a href="<?= APP_URL ?>/admin-users" class="card-link">
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
