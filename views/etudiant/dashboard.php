<?php
// ============================================================
//  views/etudiant/dashboard.php
//  Dashboard étudiant
// ============================================================

$page_active = 'dashboard';

// Récupération des données
$utilisateur = trouver_utilisateur_par_id($_SESSION['user_id']);
$stats = get_stats_etudiant($_SESSION['user_id']);
$sessions_a_venir = get_sessions_a_venir($_SESSION['user_id'], 5, 'apprenant');

// Messages non lus
$nb_non_lus = 0;
if (function_exists('compter_messages_non_lus')) {
    $nb_non_lus = compter_messages_non_lus($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/etudiant/dashboard.css">
    
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main style="padding: 24px 32px;">
    
    <!-- Welcome Section avec photo de profil -->
    <div class="animate-fadeInUp" style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                    <i class="fa-regular fa-hand-wave" style="color: #F59E0B; margin-right: 12px;"></i>
                    Bonjour, <?= e($utilisateur['prenom']) ?>
                </h1>
                <p style="color: #64748B; font-size: 14px;">
                    <?php
                    $jours = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi',
                              'Wednesday'=>'Mercredi','Thursday'=>'Jeudi',
                              'Friday'=>'Vendredi','Saturday'=>'Samedi'];
                    $mois  = ['January'=>'janvier','February'=>'février','March'=>'mars',
                              'April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet',
                              'August'=>'août','September'=>'septembre','October'=>'octobre',
                              'November'=>'novembre','December'=>'décembre'];
                    echo $jours[date('l')] . ' ' . date('d') . ' ' . $mois[date('F')] . ' ' . date('Y');
                    ?>
                    — Prêt à apprendre aujourd'hui ?
                </p>
            </div>
            
            <!-- PHOTO DE PROFIL -->
            <div class="relative">
                <?php if (!empty($utilisateur['photo'])): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" 
                         class="w-14 h-14 rounded-2xl object-cover shadow-md border-2 border-white"
                         alt="Photo de profil">
                <?php else: ?>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        <?= strtoupper(substr($utilisateur['prenom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- STATS CARDS -->
    <div class="grid-4 animate-fadeInUp" style="margin-bottom: 28px;">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Sessions réalisées</span>
                <div class="stat-icon" style="background: rgba(91,79,232,0.1); color: #5B4FE8;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['total_sessions'] ?? 0) ?></div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Sessions à venir</span>
                <div class="stat-icon" style="background: rgba(15,196,167,0.1); color: #0FC4A7;">
                    <i class="fa-solid fa-calendar"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['sessions_a_venir'] ?? 0) ?></div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Mentors contactés</span>
                <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #F59E0B;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['mentors_contactes'] ?? 0) ?></div>
        </div>
        
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span class="stat-label">Évaluations</span>
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #EF4444;">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
            <div class="stat-value"><?= number_format($stats['evaluations'] ?? 0) ?></div>
        </div>
    </div>
    
    <!-- PROCHAINES SESSIONS -->
    <div class="animate-slideInRight" style="background: #fff; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-calendar-days" style="color: #5B4FE8;"></i>
                Prochaines sessions
            </h2>
            <a href="<?= APP_URL ?>/mes-sessions" style="font-size: 13px; color: #5B4FE8; text-decoration: none; font-weight: 500;">
                Voir tout <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        
        <?php if (empty($sessions_a_venir)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
            </div>
            <p style="color: #64748B; font-size: 14px; margin-bottom: 8px;">Aucune session à venir</p>
            <p style="color: #94A3B8; font-size: 13px;">Trouvez un mentor pour commencer votre apprentissage</p>
            <a href="<?= APP_URL ?>/recherche" class="btn-message" style="display: inline-flex; margin-top: 20px; background: #5B4FE8; color: #fff;">
                <i class="fa-solid fa-magnifying-glass"></i> Trouver un mentor
            </a>
        </div>
        <?php else: ?>
        <div style="padding: 8px 0;">
            <?php foreach ($sessions_a_venir as $sess): ?>
            <div style="display: flex; align-items: center; gap: 16px; padding: 16px 24px; border-bottom: 1px solid #F1F5F9; transition: background 0.2s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background=''">
                
                <div style="background: #F8FAFC; border-radius: 16px; padding: 10px 16px; text-align: center; min-width: 80px;">
                    <p style="font-size: 11px; font-weight: 600; color: #5B4FE8; text-transform: uppercase; margin-bottom: 4px;">
                        <?= strtoupper(date('M', strtotime($sess['date_session']))) ?>
                    </p>
                    <p style="font-size: 24px; font-weight: 800; color: #0F172A;">
                        <?= date('d', strtotime($sess['date_session'])) ?>
                    </p>
                </div>
                
                <div style="flex: 1;">
                    <p style="font-weight: 600; color: #0F172A; margin-bottom: 4px;">
                        <?= e($sess['matiere_nom']) ?>
                        <span style="color: #94A3B8; font-weight: 400;">avec</span>
                        <?= e($sess['mentor_nom_complet']) ?>
                    </p>
                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                        <p style="font-size: 12px; color: #64748B;">
                            <i class="fa-regular fa-clock"></i>
                            <?= date('H:i', strtotime($sess['heure_debut'])) ?> — <?= date('H:i', strtotime($sess['heure_fin'])) ?>
                        </p>
                        <p style="font-size: 12px; color: #64748B;">
                            <i class="fa-solid fa-<?= $sess['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                            <?= $sess['mode_session'] === 'en_ligne' ? ' En ligne' : ' Présentiel' ?>
                        </p>
                    </div>
                    <?php if ($sess['statut'] === 'confirmee' && !empty($sess['lien_session'])): ?>
                    <a href="<?= e($sess['lien_session']) ?>" target="_blank" style="font-size: 11px; color: #0FC4A7; text-decoration: none; margin-top: 6px; display: inline-block;">
                        <i class="fa-solid fa-link"></i> Lien de la session
                    </a>
                    <?php endif; ?>
                </div>
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <?php if ($sess['statut'] === 'confirmee'): ?>
                        <a href="/conversation&user_id=<?= $sess['mentor_id'] ?>" class="btn-message tooltip">
                            <i class="fa-regular fa-message"></i>
                            <span class="tooltip-text">Envoyer un message</span>
                        </a>
                        <span class="badge-success">
                            <i class="fa-solid fa-check-circle"></i> Confirmée
                        </span>
                    <?php else: ?>
                        <span class="badge-warning">
                            <i class="fa-solid fa-hourglass-half"></i> En attente
                        </span>
                    <?php endif; ?>
                </div>
                
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
</main>

<script>
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.stat-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>
