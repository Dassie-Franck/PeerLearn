<?php
// ============================================================
//  views/layouts/sidebar_mentor.php
//  Sidebar espace mentor - Style moderne avec dégradé vert
//  Variables attendues : $page_active, $utilisateur, $demandes_en_attente, $nb_non_lus
// ============================================================
$page_active = $page_active ?? '';
$demandes_en_attente = $demandes_en_attente ?? 0;
$nb_non_lus = $nb_non_lus ?? 0;
?>

<style>
    /* ==================== SIDEBAR STYLES ==================== */
    .sidebar-mentor {
        width: 280px;
        background: linear-gradient(180deg, #064E3B 0%, #022C22 100%);
        color: #fff;
        transition: transform 0.3s ease;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 1000;
        overflow-y: auto;
        transform: translateX(-100%);
    }

    .sidebar-mentor.open {
        transform: translateX(0);
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

    .nav-badge {
        background: #EF4444;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: auto;
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
        z-index: 999;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .sidebar-overlay.active {
        display: block;
        opacity: 1;
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
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        z-index: 100;
        align-items: center;
        justify-content: space-between;
    }

    .mobile-menu-btn {
        padding: 8px;
        border-radius: 12px;
        transition: background 0.2s;
        cursor: pointer;
    }

    /* Main content wrapper */
    .main-content-wrapper-mentor {
        flex: 1;
        min-height: 100vh;
        background: #F8FAFC;
        transition: margin-left 0.3s ease;
    }

    /* Desktop */
    @media (min-width: 769px) {
        .sidebar-mentor {
            transform: translateX(0) !important;
        }
        .main-content-wrapper-mentor {
            margin-left: 280px;
        }
        .mobile-topbar-mentor {
            display: none !important;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .main-content-wrapper-mentor {
            margin-left: 0 !important;
            padding-top: 60px;
        }
        .mobile-topbar-mentor {
            display: flex;
        }
    }
</style>

<!-- Overlay -->
<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar-mentor" id="main-sidebar">
    <div class="sidebar-header">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center">
                <span class="text-white font-bold text-lg">P</span>
            </div>
            <span class="logo-text text-xl font-bold" style="background: linear-gradient(135deg, #fff, #5EEAD4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Peer<span style="color: #0FC4A7;">Learn</span>
            </span>
        </div>
        <p class="text-xs text-teal-300 mt-2 logo-text">Espace Mentor</p>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= APP_URL ?>/mentor" class="nav-item <?= $page_active === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Tableau de bord</span>
        </a>

        <a href="<?= APP_URL ?>/demandes" class="nav-item <?= $page_active === 'demandes' ? 'active' : '' ?>">
            <i class="fa-solid fa-clock"></i>
            <span>Demandes reçues</span>
            <?php if ($demandes_en_attente > 0): ?>
            <span class="nav-badge"><?= $demandes_en_attente ?></span>
            <?php endif; ?>
        </a>

        <a href="<?= APP_URL ?>/disponibilites" class="nav-item <?= $page_active === 'disponibilites' ? 'active' : '' ?>">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Disponibilités</span>
        </a>

        <a href="<?= APP_URL ?>/mes-sessions" class="nav-item <?= $page_active === 'sessions' ? 'active' : '' ?>">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Mes sessions</span>
        </a>

        <a href="<?= APP_URL ?>/mentor-profil" class="nav-item <?= $page_active === 'profil' ? 'active' : '' ?>">
            <i class="fa-solid fa-user"></i>
            <span>Mon profil mentor</span>
        </a>

        <a href="<?= APP_URL ?>/messages" class="nav-item <?= $page_active === 'messages' ? 'active' : '' ?>">
            <i class="fa-solid fa-message"></i>
            <span>Messages</span>
            <?php if ($nb_non_lus > 0): ?>
            <span class="nav-badge"><?= $nb_non_lus ?></span>
            <?php endif; ?>
        </a>

        <div style="margin: 16px 0 8px; border-top: 1px solid rgba(255,255,255,0.1);"></div>

        <div class="nav-section-title">Espace Étudiant</div>

        <a href="<?= APP_URL ?>/dashboard" class="nav-item <?= $page_active === 'student-dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Dashboard étudiant</span>
        </a>
    </nav>

    <div style="position: absolute; bottom: 20px; left: 0; right: 0; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 12px; padding: 8px 12px; margin-bottom: 8px; background: rgba(255,255,255,0.05); border-radius: 14px;">
            <?php if (!empty($utilisateur['photo'])): ?>
                <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" class="w-10 h-10 rounded-xl object-cover">
            <?php else: ?>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($utilisateur['prenom'] ?? 'M', 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate"><?= e($utilisateur['prenom'] ?? '') ?> <?= e($utilisateur['nom'] ?? '') ?></p>
                <p class="text-xs text-teal-400">
                    <i class="fa-regular fa-circle" style="font-size: 8px;"></i> Mentor
                </p>
            </div>
        </div>

        <a href="<?= APP_URL ?>/logout" class="nav-item" style="color: #EF4444;">
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
function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

function closeSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
}

// Fermer avec Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSidebar();
});

// Fermer sur resize desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeSidebar();
});
</script>
