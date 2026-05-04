<?php
// views/layouts/navbar_admin.php
$page_active = $page_active ?? '';
?>
<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="<?= APP_URL ?>/admin" style="text-decoration:none">
            Peer<span style="color:#EF4444">Learn</span>
        </a>
        <div class="sidebar-badge" style="color:#EF4444">Administration</div>
    </div>

    <nav class="sidebar-nav">

        <a href="<?= APP_URL ?>/admin"
           class="nav-link <?= $page_active === 'admin' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>

        <p class="sidebar-section">Utilisateurs</p>

        <a href="<?= APP_URL ?>/admin-users"
           class="nav-link <?= $page_active === 'admin-users' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-users"></i> Tous les comptes
        </a>

        <a href="<?= APP_URL ?>/admin-users&filtre=mentors_en_attente"
           class="nav-link <?= $page_active === 'admin-mentors' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-user-clock"></i> Demandes mentor
            <?php
            // Badge nombre de demandes en attente
            $pdo  = get_pdo();
            $nb   = (int)$pdo->query("
                SELECT COUNT(*) FROM utilisateurs
                WHERE est_mentor = 1 AND mentor_valide = 0 AND statut = 'actif'
            ")->fetchColumn();
            if ($nb > 0):
            ?>
            <span style="margin-left:auto;background:#EF4444;color:#fff;
                         font-size:11px;font-weight:700;padding:2px 7px;
                         border-radius:10px;flex-shrink:0">
                <?= $nb ?>
            </span>
            <?php endif; ?>
        </a>

        <p class="sidebar-section">Contenu</p>

        <a href="<?= APP_URL ?>/admin-signalements"
           class="nav-link <?= $page_active === 'admin-signalements' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-flag"></i> Signalements
            <?php
            $nb_sig = (int)$pdo->query("
                SELECT COUNT(*) FROM signalements WHERE statut = 'en_attente'
            ")->fetchColumn();
            if ($nb_sig > 0):
            ?>
            <span style="margin-left:auto;background:#EF4444;color:#fff;
                         font-size:11px;font-weight:700;padding:2px 7px;
                         border-radius:10px;flex-shrink:0">
                <?= $nb_sig ?>
            </span>
            <?php endif; ?>
        </a>

        <a href="<?= APP_URL ?>/admin-matieres"
           class="nav-link <?= $page_active === 'admin-matieres' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-book"></i> Matieres
        </a>

        <p class="sidebar-section">Systeme</p>

        <a href="<?= APP_URL ?>/admin-journal"
           class="nav-link <?= $page_active === 'admin-journal' ? 'active-red' : '' ?>">
            <i class="fa-solid fa-scroll"></i> Journal d activite
        </a>

    </nav>

    <div class="sidebar-user">
        <div class="sidebar-user-row">
            <div class="avatar" style="background:#EF4444">
                <i class="fa-solid fa-shield-halved" style="font-size:14px"></i>
            </div>
            <div style="min-width:0">
                <div class="sidebar-username"><?= e($_SESSION['nom'] ?? '') ?></div>
                <div class="sidebar-role" style="color:#EF4444">
                    <i class="fa-solid fa-circle" style="font-size:8px"></i> Administrateur
                </div>
            </div>
        </div>
        <a href="<?= APP_URL ?>/logout" class="logout-link">
            <i class="fa-solid fa-right-from-bracket"></i> Deconnexion
        </a>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="font-syne" style="font-size:16px;font-weight:700">
            Peer<span style="color:#EF4444">Learn</span>
            <span style="font-size:12px;color:#9CA3AF;font-weight:400;margin-left:6px">Admin</span>
        </span>
        <div style="width:36px"></div>
    </div>

<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
</script>

<style>
/* Couleur rouge pour l espace admin */
.nav-link.active-red {
    background  : rgba(239,68,68,.12);
    color       : #EF4444;
    border-left-color: #EF4444;
    font-weight : 500;
}
.nav-link:hover { background:rgba(239,68,68,.07); color:#EF4444; }
</style>
