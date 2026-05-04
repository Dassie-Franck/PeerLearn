<?php
// ============================================================
//  views/layouts/sidebar_etudiant.php
//  Sidebar étudiante — style moderne unifié
//  Variables attendues : 
//    - $page_active : string (dashboard, recherche, sessions, messages, profil, mentor-dashboard, disponibilites, demandes)
//    - $nb_non_lus : int (nombre de messages non lus)
//    - $utilisateur : array (infos utilisateur avec est_mentor)
// ============================================================
$page_active = $page_active ?? '';
$nb_non_lus = $nb_non_lus ?? 0;
?>
<style>
    /* ==================== SIDEBAR STYLES ==================== */
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
    
    .sidebar::-webkit-scrollbar { width: 6px; }
    .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    
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
        font-size: 14px;
        font-weight: 500;
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
    
    /* Badge pour les notifications */
    .nav-badge {
        background: #EF4444;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: auto;
    }
    
    /* Notification badge animé */
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #EF4444;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 20px;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    
    /* Mobile topbar */
    .mobile-topbar {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 16px 20px;
        border-bottom: 1px solid #E2E8F0;
        z-index: 45;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .mobile-menu-btn {
        padding: 8px;
        border-radius: 12px;
        transition: background 0.2s;
        cursor: pointer;
    }
    
    .mobile-menu-btn:hover {
        background: #F1F5F9;
    }
    
    /* Main content wrapper */
    .main-content-wrapper {
        margin-left: 280px;
        flex: 1;
        min-height: 100vh;
        background: #F8FAFC;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .sidebar { width: 80px; }
        .sidebar .logo-text, .sidebar .nav-item span { display: none; }
        .sidebar .nav-item { justify-content: center; }
        .sidebar .nav-item i { margin: 0; }
        .main-content-wrapper { margin-left: 80px; }
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            width: 280px;
        }
        .sidebar .logo-text, .sidebar .nav-item span { display: inline; }
        .sidebar .nav-item { justify-content: flex-start; }
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        .main-content-wrapper {
            margin-left: 0;
        }
        .mobile-topbar {
            display: flex;
        }
        body {
            padding-top: 70px;
        }
    }
    
    /* Overlay pour mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 39;
    }
    
    .sidebar-overlay.active {
        display: block;
    }
</style>

<!-- Overlay pour fermer la sidebar sur mobile -->
<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="main-sidebar">
    <div class="sidebar-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
                <span class="text-white font-bold text-lg">P</span>
            </div>
            <span class="logo-text text-xl font-bold" style="background: linear-gradient(135deg, #fff, #94A3B8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PeerLearn</span>
        </div>
        <p class="text-xs text-gray-400 mt-2 logo-text">Espace Étudiant</p>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/dashboard" class="nav-item <?= $page_active === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Tableau de bord</span>
        </a>
        
        <a href="<?= APP_URL ?>/recherche" class="nav-item <?= $page_active === 'recherche' ? 'active' : '' ?>">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>Trouver un mentor</span>
        </a>
        
        <a href="<?= APP_URL ?>/mes-sessions" class="nav-item <?= $page_active === 'sessions' ? 'active' : '' ?>">
            <i class="fa-solid fa-calendar"></i>
            <span>Mes sessions</span>
        </a>
        
        <a href="<?= APP_URL ?>/messages" class="nav-item <?= $page_active === 'messages' ? 'active' : '' ?>">
            <i class="fa-solid fa-message"></i>
            <span>Messages</span>
            <?php if (!empty($nb_non_lus) && $nb_non_lus > 0): ?>
            <span class="nav-badge"><?= $nb_non_lus ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?= APP_URL ?>/profil" class="nav-item <?= $page_active === 'profil' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Mon profil</span>
        </a>
        
        <!-- Espace Mentor (si l'utilisateur est mentor) -->
        <?php if (!empty($utilisateur['est_mentor'])): ?>
        <div style="margin: 16px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);"></div>
        <a href="<?= APP_URL ?>/mentor" class="nav-item <?= $page_active === 'mentor-dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Espace Mentor</span>
            <i class="fa-solid fa-arrow-right" style="font-size: 12px; margin-left: auto;"></i>
        </a>
        <?php endif; ?>
    </nav>
    
    <!-- Footer sidebar avec aide et déconnexion -->
    <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 16px;">
        <a href="<?= APP_URL ?>/aide" class="nav-item" style="margin-bottom: 8px;">
            <i class="fa-solid fa-circle-question"></i>
            <span>Aide</span>
        </a>
        <a href="<?= APP_URL ?>/logout" class="nav-item" style="color: #EF4444;">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Mobile topbar -->
<div class="mobile-topbar" id="mobile-topbar">
    <div class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars text-gray-600 text-xl"></i>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
            <span class="text-white font-bold text-sm">P</span>
        </div>
        <span class="font-semibold text-gray-800">PeerLearn</span>
    </div>
    <?php if (!empty($utilisateur['photo'])): ?>
        <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" class="w-8 h-8 rounded-full object-cover">
    <?php else: ?>
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
            <?= strtoupper(substr($utilisateur['prenom'] ?? 'U', 0, 1)) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Main content wrapper -->
<div class="main-content-wrapper" id="main-content-wrapper">

<script>
// ==================== SIDEBAR FUNCTIONS ====================
function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
}

// Fermer la sidebar quand on redimensionne en desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeSidebar();
    }
    checkMobileTopbar();
});

// Gérer l'affichage du topbar mobile
function checkMobileTopbar() {
    const topbar = document.getElementById('mobile-topbar');
    const mainContent = document.getElementById('main-content-wrapper');
    if (window.innerWidth <= 768) {
        if (topbar) topbar.style.display = 'flex';
        if (mainContent) mainContent.style.paddingTop = '70px';
    } else {
        if (topbar) topbar.style.display = 'none';
        if (mainContent) mainContent.style.paddingTop = '0';
    }
}

// Fermer la sidebar avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    checkMobileTopbar();
});
</script>
