<?php
// ============================================================
//  views/layouts/sidebar_mentor.php
//  Sidebar espace mentor - Style moderne avec dégradé vert
//  Variable attendue : $page_active (string)
// ============================================================
$page_active = $page_active ?? '';
?>

<style>
    /* ==================== SIDEBAR STYLES ==================== */
    .sidebar-mentor {
        width: 280px;
        background: linear-gradient(180deg, #064E3B 0%, #022C22 100%);
        color: #fff;
        transition: all 0.3s ease;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 40;
        overflow-y: auto;
    }
    
    .sidebar-mentor::-webkit-scrollbar { width: 6px; }
    .sidebar-mentor::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .sidebar-mentor::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    
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
        background: linear-gradient(135deg, #0FC4A7, #0D9488);
        color: #fff;
        box-shadow: 0 4px 12px rgba(15,196,167,0.3);
    }
    
    .nav-item i { width: 24px; font-size: 18px; }
    
    .nav-section-title {
        font-size: 11px;
        font-weight: 700;
        color: #5EEAD4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0 12px;
        margin: 16px 0 8px;
    }
    
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
    
    /* Mobile topbar */
    .mobile-topbar-mentor {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #022C22;
        color: #fff;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        z-index: 45;
        align-items: center;
        justify-content: space-between;
    }
    
    .mobile-menu-btn {
        padding: 8px;
        border-radius: 12px;
        transition: background 0.2s;
        cursor: pointer;
    }
    
    .mobile-menu-btn:hover {
        background: rgba(255,255,255,0.1);
    }
    
    /* Main content wrapper */
    .main-content-wrapper-mentor {
        margin-left: 280px;
        flex: 1;
        min-height: 100vh;
        background: #F8FAFC;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .sidebar-mentor { width: 80px; }
        .sidebar-mentor .logo-text, .sidebar-mentor .nav-item span { display: none; }
        .sidebar-mentor .nav-item { justify-content: center; }
        .sidebar-mentor .nav-item i { margin: 0; }
        .main-content-wrapper-mentor { margin-left: 80px; }
    }
    
    @media (max-width: 768px) {
        .sidebar-mentor {
            transform: translateX(-100%);
            width: 280px;
        }
        .sidebar-mentor .logo-text, .sidebar-mentor .nav-item span { display: inline; }
        .sidebar-mentor .nav-item { justify-content: flex-start; }
        .sidebar-mentor.mobile-open {
            transform: translateX(0);
        }
        .main-content-wrapper-mentor {
            margin-left: 0;
        }
        .mobile-topbar-mentor {
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

<!-- Sidebar Mentor -->
<aside class="sidebar-mentor" id="main-sidebar">
    <div class="sidebar-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center">
                <span class="text-white font-bold text-lg">P</span>
            </div>
            <span class="logo-text text-xl font-bold" style="background: linear-gradient(135deg, #fff, #5EEAD4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Peer<span style="color: #0FC4A7; -webkit-text-fill-color: #0FC4A7;">Learn</span>
            </span>
        </div>
        <p class="text-xs text-teal-300 mt-2 logo-text">Espace Mentor</p>
    </div>
    
    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/?url=mentor" class="nav-item <?= $page_active === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Tableau de bord</span>
        </a>
        
        <a href="<?= APP_URL ?>/?url=demandes" class="nav-item <?= $page_active === 'demandes' ? 'active' : '' ?>">
            <i class="fa-solid fa-clock"></i>
            <span>Demandes reçues</span>
            <?php if (!empty($demandes_en_attente) && $demandes_en_attente > 0): ?>
            <span class="nav-badge"><?= $demandes_en_attente ?></span>
            <?php endif; ?>
        </a>
        
        <a href="<?= APP_URL ?>/?url=disponibilites" class="nav-item <?= $page_active === 'disponibilites' ? 'active' : '' ?>">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Disponibilités</span>
        </a>
        
        <a href="<?= APP_URL ?>/?url=mes-sessions" class="nav-item <?= $page_active === 'sessions' ? 'active' : '' ?>">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Mes sessions</span>
        </a>
        
        <a href="<?= APP_URL ?>/?url=mentor-profil" class="nav-item <?= $page_active === 'profil' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Mon profil mentor</span>
        </a>
        
        <a href="<?= APP_URL ?>/?url=messages" class="nav-item <?= $page_active === 'messages' ? 'active' : '' ?>">
            <i class="fa-solid fa-message"></i>
            <span>Messages</span>
            <?php if (!empty($nb_non_lus) && $nb_non_lus > 0): ?>
            <span class="nav-badge"><?= $nb_non_lus ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Séparateur et retour espace étudiant -->
        <div style="margin: 16px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);"></div>
        
        <div class="nav-section-title">Espace Étudiant</div>
        
        <a href="<?= APP_URL ?>/?url=dashboard" class="nav-item <?= $page_active === 'student-dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Dashboard étudiant</span>
        </a>
        
        
    </nav>
    
    <!-- Footer sidebar avec informations utilisateur -->
    <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 12px; padding: 8px 12px; margin-bottom: 8px; background: rgba(255,255,255,0.05); border-radius: 14px;">
            <?php if (!empty($utilisateur['photo'])): ?>
                <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" 
                     class="w-10 h-10 rounded-xl object-cover">
            <?php else: ?>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($utilisateur['prenom'] ?? 'M', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate"><?= e($utilisateur['prenom'] ?? '') ?> <?= e($utilisateur['nom'] ?? '') ?></p>
                <p class="text-xs text-teal-400">
                    <i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> Mentor
                </p>
            </div>
        </div>
       
        
        <a href="<?= APP_URL ?>/?url=logout" class="nav-item" style="color: #EF4444;">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>

<!-- Mobile topbar -->
<div class="mobile-topbar-mentor" id="mobile-topbar">
    <div class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars text-white text-xl"></i>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center">
            <span class="text-white font-bold text-sm">P</span>
        </div>
        <span class="font-semibold text-white">PeerLearn Mentor</span>
    </div>
    <?php if (!empty($utilisateur['photo'])): ?>
        <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" class="w-8 h-8 rounded-full object-cover">
    <?php else: ?>
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center text-white font-bold text-sm">
            <?= strtoupper(substr($utilisateur['prenom'] ?? 'M', 0, 1)) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Main content wrapper -->
<div class="main-content-wrapper-mentor" id="main-content-wrapper">

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

// Détecter les écrans mobiles pour afficher le topbar
function checkMobileAndTopbar() {
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

// Fermer la sidebar quand on redimensionne en desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        closeSidebar();
    }
    checkMobileAndTopbar();
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    checkMobileAndTopbar();
});

// Fermer la sidebar en cliquant à l'extérieur sur mobile
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    
    if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('mobile-open')) {
        if (!sidebar.contains(e.target) && !mobileBtn?.contains(e.target)) {
            closeSidebar();
        }
    }
});
</script>