<?php
// ============================================================
//  views/etudiant/mes_sessions.php
//  Liste des sessions de l'utilisateur avec filtres et actions
// ============================================================

$page_active = 'sessions';
$filtre_actif = $_GET['statut'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes sessions — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/etudiant/mes-sessions.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/sidebar_etudiant.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main class="flex-1 p-8">
    
    <!-- En-tête -->
    <div class="animate-fadeInUp mb-7">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    <i class="fa-solid fa-calendar-days" style="color: #5B4FE8; margin-right: 12px;"></i>
                    Mes sessions
                </h1>
                <p class="text-gray-500 text-sm">Consultez et gérez toutes vos sessions de mentorat.</p>
            </div>
            <a href="<?= APP_URL ?>/recherche" class="btn-evaluate inline-flex items-center gap-2 px-6 py-2.5">
                <i class="fa-solid fa-plus"></i> Réserver une session
            </a>
        </div>
    </div>
    
    <!-- Messages flash -->
    <?php if (!empty($succes)): ?>
    <div class="animate-fadeInUp flash-success">
        <i class="fa-solid fa-check-circle"></i>
        <p class="m-0 text-sm"><?= e($succes) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($erreur)): ?>
    <div class="animate-fadeInUp flash-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <p class="m-0 text-sm"><?= e($erreur) ?></p>
    </div>
    <?php endif; ?>
    
    <!-- Filtres par statut -->
    <div class="animate-fadeInUp flex flex-wrap gap-2.5 mb-6">
        <?php
        $filtres = [
            ''          => ['label' => 'Toutes', 'icon' => 'fa-list'],
            'en_attente'=> ['label' => 'En attente', 'icon' => 'fa-clock'],
            'confirmee' => ['label' => 'Confirmées', 'icon' => 'fa-check-circle'],
            'terminee'  => ['label' => 'Terminées', 'icon' => 'fa-circle-check'],
            'annulee'   => ['label' => 'Annulées', 'icon' => 'fa-ban'],
        ];
        foreach ($filtres as $val => $info):
        ?>
        <a href="<?= APP_URL ?>/mes-sessions<?= $val ? '&statut=' . $val : '' ?>"
           class="filter-btn <?= $filtre_actif === $val ? 'active' : '' ?>">
            <i class="fa-regular <?= $info['icon'] ?>"></i> <?= $info['label'] ?>
        </a>
        <?php endforeach; ?>
    </div>
    
    <!-- LISTE DES SESSIONS -->
    <?php if (empty($sessions)): ?>
    <div class="animate-fadeInUp empty-state">
        <div class="empty-icon">
            <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
        </div>
        <p class="text-base font-semibold text-gray-900 mb-2">Aucune session trouvée</p>
        <p class="text-gray-500 text-sm mb-6">Réservez votre première session avec un mentor.</p>
        <a href="<?= APP_URL ?>/recherche" class="btn-evaluate inline-flex items-center gap-2">
            <i class="fa-solid fa-magnifying-glass"></i> Trouver un mentor
        </a>
    </div>
    
    <?php else: ?>
    <div class="flex flex-col gap-4">
        <?php foreach ($sessions as $s):
            $user_id = $_SESSION['user_id'];
            $est_mentor = ($s['mentor_id'] == $user_id);
            $autre_nom = $est_mentor ? ($s['apprenant_nom_complet'] ?? $s['apprenant_nom'] ?? '') : ($s['mentor_nom_complet'] ?? $s['mentor_nom'] ?? '');
            $role_label = $est_mentor ? 'Mentor' : 'Apprenant';
            
            $badge = match($s['statut']) {
                'en_attente' => ['class' => 'badge-pending', 'icon' => 'fa-clock', 'text' => 'En attente'],
                'confirmee'  => ['class' => 'badge-confirmed', 'icon' => 'fa-check-circle', 'text' => 'Confirmée'],
                'terminee'   => ['class' => 'badge-completed', 'icon' => 'fa-circle-check', 'text' => 'Terminée'],
                'annulee'    => ['class' => 'badge-cancelled', 'icon' => 'fa-ban', 'text' => 'Annulée'],
                default      => ['class' => 'badge-pending', 'icon' => 'fa-question', 'text' => $s['statut']],
            };
        ?>
        <div class="session-card">
            <div class="flex items-start gap-5 flex-wrap">
                <!-- Date bloc -->
                <div class="session-date-box">
                    <p class="text-xs font-semibold text-violet uppercase mb-1">
                        <?= strtoupper(date('M', strtotime($s['date_session']))) ?>
                    </p>
                    <p class="text-3xl font-extrabold text-gray-900">
                        <?= date('d', strtotime($s['date_session'])) ?>
                    </p>
                    <p class="text-[10px] text-gray-400">
                        <?= date('Y', strtotime($s['date_session'])) ?>
                    </p>
                </div>
                
                <!-- Infos session -->
                <div class="flex-1">
                    <div class="flex items-center gap-2.5 flex-wrap mb-2">
                        <h3 class="text-base font-bold text-gray-900">
                            <?= e($s['matiere_nom']) ?>
                        </h3>
                        <span class="<?= $badge['class'] ?>">
                            <i class="fa-regular <?= $badge['icon'] ?>"></i> <?= $badge['text'] ?>
                        </span>
                        <span class="role-badge">
                            <i class="fa-solid fa-<?= $est_mentor ? 'chalkboard-user' : 'user-graduate' ?>"></i> <?= $role_label ?>
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-1.5">
                        <?= $est_mentor ? 'Apprenant :' : 'Mentor :' ?>
                        <span class="font-semibold text-gray-900"><?= e($autre_nom) ?></span>
                    </p>
                    
                    <div class="flex items-center gap-4 flex-wrap">
                        <p class="text-sm text-gray-500">
                            <i class="fa-regular fa-clock"></i>
                            <?= date('H:i', strtotime($s['heure_debut'])) ?> — <?= date('H:i', strtotime($s['heure_fin'])) ?>
                        </p>
                        <p class="text-sm <?= $s['mode_session'] === 'en_ligne' ? 'text-teal-600' : 'text-amber-600' ?>">
                            <i class="fa-solid fa-<?= $s['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                            <?= $s['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                        </p>
                    </div>
                    
                    <!-- Lien visio si confirmée en ligne -->
                    <?php if ($s['statut'] === 'confirmee' && $s['mode_session'] === 'en_ligne' && !empty($s['lien_session'])): ?>
                    <a href="<?= e($s['lien_session']) ?>" target="_blank" class="inline-flex items-center gap-1.5 mt-3 text-sm text-teal-600 hover:text-teal-700 no-underline">
                        <i class="fa-solid fa-link"></i> Rejoindre la session
                    </a>
                    <?php endif; ?>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col gap-2 min-w-[100px]">
                    <!-- Évaluer (session terminée, étudiant, pas encore évalué) -->
                    <?php if ($s['statut'] === 'terminee' && !$est_mentor && empty($s['deja_evalue'])): ?>
                    <a href="<?= APP_URL ?>/evaluation/formulaire&session_id=<?= $s['id'] ?>" class="btn-evaluate text-center">
                        <i class="fa-regular fa-star"></i> Évaluer
                    </a>
                    <?php endif; ?>
                    
                    <!-- Annuler (session active) -->
                    <?php if (in_array($s['statut'], ['en_attente', 'confirmee'])): ?>
                    <button onclick="ouvrirModalAnnulation(<?= $s['id'] ?>)" class="btn-cancel">
                        <i class="fa-regular fa-trash-can"></i> Annuler
                    </button>
                    <?php endif; ?>
                    
                    <!-- Message -->
                    <a href="<?= APP_URL ?>/conversation&user_id=<?= $est_mentor ? ($s['apprenant_id'] ?? 0) : ($s['mentor_id'] ?? 0) ?>" class="btn-message">
                        <i class="fa-regular fa-message"></i> Message
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
</main>

<!-- MODAL ANNULATION -->
<div id="modal-annulation" class="modal">
    <div class="modal-box">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Annuler la session</h2>
                <p class="text-xs text-gray-500 mt-0.5">Si l'annulation a lieu moins de 2h avant la session, elle sera considérée comme tardive.</p>
            </div>
            <button onclick="fermerModal()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="px-6 py-5">
            <form method="POST" action="<?= APP_URL ?>/annuler-session">
                <?= csrf_field() ?>
                <input type="hidden" name="session_id" id="modal-session-id">
                <div class="mb-5">
                    <label class="form-label">Motif (optionnel)</label>
                    <textarea name="motif" rows="3" class="form-textarea" placeholder="Raison de l'annulation..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="fermerModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Retour</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ==================== MODAL ANNULATION ====================
function ouvrirModalAnnulation(sessionId) {
    document.getElementById('modal-session-id').value = sessionId;
    document.getElementById('modal-annulation').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function fermerModal() {
    document.getElementById('modal-annulation').classList.remove('open');
    document.body.style.overflow = '';
}

// Fermer en cliquant à l'extérieur
document.getElementById('modal-annulation').addEventListener('click', function(e) {
    if (e.target === this) fermerModal();
});

// Fermer avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') fermerModal();
});

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

document.querySelectorAll('.session-card').forEach(el => {
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>
