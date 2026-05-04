<?php
// ============================================================
//  views/mentor/demandes.php
//  Gestion des demandes de mentorat
// ============================================================

$page_active = 'demandes';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes reçues — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/mentor/demande.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<!-- ==================== MODAL REFUS ==================== -->
<div id="modal-refus" class="modal-overlay" onclick="closeModalRefusOutside(event)">
    <div class="modal-box">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Refuser la demande</h2>
                <p class="text-xs text-gray-500 mt-0.5">Le motif est optionnel mais recommandé.</p>
            </div>
            <button onclick="closeModalRefus()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="px-6 py-5">
            <form method="POST" action="<?= APP_URL ?>/?url=refuser" id="form-refus">
                <?= csrf_field() ?>
                <input type="hidden" name="session_id" id="refus-session-id">
                <div class="mb-5">
                    <label class="form-label">Motif du refus (optionnel)</label>
                    <textarea name="motif" rows="3" class="input-field resize-none" placeholder="Ex : je ne suis pas disponible à cet horaire..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeModalRefus()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Annuler</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== MODAL LIEN VISIO ==================== -->
<div id="modal-lien" class="modal-overlay" onclick="closeModalLienOutside(event)">
    <div class="modal-box">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Confirmer la session</h2>
                <p class="text-xs text-gray-500 mt-0.5">Session en ligne — un lien est requis.</p>
            </div>
            <button onclick="closeModalLien()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="px-6 py-5">
            <form method="POST" action="<?= APP_URL ?>/?url=confirmer" id="form-lien">
                <?= csrf_field() ?>
                <input type="hidden" name="session_id" id="lien-session-id">
                <div class="mb-5">
                    <label class="form-label">Lien Zoom / Meet / Jitsi <span class="text-red-500">*</span></label>
                    <input type="url" name="lien_session" class="input-field" placeholder="https://meet.google.com/..." required>
                    <p class="text-xs text-gray-400 mt-1.5">Ce lien sera transmis à l'étudiant après confirmation.</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeModalLien()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Annuler</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold transition">Confirmer la session</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main class="p-8">
    
    <!-- En-tête -->
    <div class="animate-fadeInUp mb-7">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    <i class="fa-solid fa-clock" style="color: #0FC4A7; margin-right: 12px;"></i>
                    Demandes reçues
                </h1>
                <p class="text-gray-500 text-sm">Gérez les demandes de réservation de vos créneaux.</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <?php if ($compteurs['en_attente'] > 0): ?>
                <span class="stat-counter bg-amber-50 text-amber-700">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <?= $compteurs['en_attente'] ?> en attente
                </span>
                <?php endif; ?>
                <?php if ($compteurs['confirmee'] > 0): ?>
                <span class="stat-counter bg-green-50 text-green-700">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <?= $compteurs['confirmee'] ?> confirmée<?= $compteurs['confirmee'] > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>
                <?php if ($compteurs['refusee'] > 0): ?>
                <span class="stat-counter bg-red-50 text-red-700">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <?= $compteurs['refusee'] ?> refusée<?= $compteurs['refusee'] > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($demandes)): ?>
    <!-- État vide -->
    <div class="animate-fadeInUp empty-state">
        <div class="empty-icon">
            <i class="fa-regular fa-bell-slash text-3xl text-gray-400"></i>
        </div>
        <p class="text-base font-semibold text-gray-900 mb-2">Aucune demande reçue</p>
        <p class="text-gray-500 text-sm mb-6">Les étudiants qui réservent tes créneaux apparaîtront ici.</p>
        <a href="<?= APP_URL ?>/?url=disponibilites" class="btn-confirm inline-flex items-center gap-2">
            <i class="fa-solid fa-calendar-plus"></i> Gérer mes disponibilités
        </a>
    </div>
    
    <?php else: 
        $en_attente = array_filter($demandes, fn($d) => $d['statut'] === 'en_attente');
        $autres = array_filter($demandes, fn($d) => $d['statut'] !== 'en_attente');
    ?>
    
    <!-- Demandes en attente -->
    <?php if (!empty($en_attente)): ?>
    <div class="animate-fadeInUp mb-8">
        <div class="flex items-center gap-3 mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                En attente
            </h2>
            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold"><?= count($en_attente) ?></span>
        </div>
        
        <div class="flex flex-col gap-4">
            <?php foreach ($en_attente as $d): ?>
            <div class="demand-card demand-card-pending">
                <div class="flex items-start gap-4 flex-wrap">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <?= strtoupper(substr($d['apprenant_prenom'], 0, 1)) ?>
                    </div>
                    
                    <!-- Infos -->
                    <div class="flex-1 min-w-[200px]">
                        <p class="font-bold text-gray-900 mb-1">
                            <?= e($d['apprenant_nom_complet']) ?>
                        </p>
                        <div class="flex flex-wrap gap-3 mb-2">
                            <span class="text-xs text-teal-600 bg-teal-50 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-book"></i> <?= e($d['matiere_nom']) ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($d['date_session'])) ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="fa-regular fa-clock"></i> <?= date('H:i', strtotime($d['heure_debut'])) ?> — <?= date('H:i', strtotime($d['heure_fin'])) ?>
                            </span>
                            <span class="text-xs <?= $d['mode_session'] === 'en_ligne' ? 'text-teal-600' : 'text-amber-600' ?>">
                                <i class="fa-solid fa-<?= $d['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                <?= $d['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            <i class="fa-regular fa-clock"></i> Reçue le <?= date('d/m/Y à H:i', strtotime($d['created_at'])) ?>
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2.5 flex-shrink-0">
                        <a href="<?= APP_URL ?>/?url=conversation&user_id=<?= $d['apprenant_id'] ?? 0 ?>" class="btn-message w-10 h-10 flex items-center justify-center tooltip">
                            <i class="fa-regular fa-message"></i>
                            <span class="tooltip-text">Envoyer un message</span>
                        </a>
                        
                        <?php if (($d['mode_session'] ?? '') === 'en_ligne'): ?>
                        <button onclick="openModalLien(<?= $d['id'] ?? 0 ?>)" class="btn-confirm px-5">
                            <i class="fa-solid fa-check-circle"></i> Confirmer
                        </button>
                        <?php else: ?>
                        <form method="POST" action="<?= APP_URL ?>/?url=confirmer" class="m-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="session_id" value="<?= $d['id'] ?? 0 ?>">
                            <input type="hidden" name="lien_session" value="">
                            <button type="submit" class="btn-confirm px-5">
                                <i class="fa-solid fa-check-circle"></i> Confirmer
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <button onclick="openModalRefus(<?= $d['id'] ?? 0 ?>)" class="btn-reject px-5">
                            <i class="fa-solid fa-times-circle"></i> Refuser
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Historique -->
    <?php if (!empty($autres)): ?>
    <div class="animate-slideInRight">
        <div class="flex items-center gap-3 mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                <i class="fa-solid fa-clock-rotate-left text-gray-500 mr-2"></i>
                Historique
            </h2>
            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold"><?= count($autres) ?></span>
        </div>
        
        <div class="flex flex-col gap-3">
            <?php foreach ($autres as $d): ?>
            <div class="demand-card p-4">
                <div class="flex items-center gap-4 flex-wrap">
                    <!-- Date -->
                    <div class="bg-gray-50 rounded-2xl p-2 text-center min-w-[70px]">
                        <p class="text-[11px] font-semibold text-teal-600 uppercase">
                            <?= strtoupper(date('M', strtotime($d['date_session']))) ?>
                        </p>
                        <p class="text-xl font-extrabold text-gray-900">
                            <?= date('d', strtotime($d['date_session'])) ?>
                        </p>
                    </div>
                    
                    <!-- Infos -->
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 mb-1">
                            <?= e($d['apprenant_nom_complet']) ?>
                            <span class="text-gray-400 font-normal">·</span>
                            <?= e($d['matiere_nom']) ?>
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <p class="text-xs text-gray-500">
                                <i class="fa-regular fa-clock"></i> <?= date('H:i', strtotime($d['heure_debut'])) ?> — <?= date('H:i', strtotime($d['heure_fin'])) ?>
                            </p>
                            <p class="text-xs <?= $d['mode_session'] === 'en_ligne' ? 'text-teal-600' : 'text-amber-600' ?>">
                                <i class="fa-solid fa-<?= $d['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                <?= $d['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                            </p>
                        </div>
                        <?php if ($d['statut'] === 'confirmee' && !empty($d['lien_session'])): ?>
                        <a href="<?= e($d['lien_session']) ?>" target="_blank" class="text-[11px] text-teal-600 no-underline mt-1.5 inline-block hover:underline">
                            <i class="fa-solid fa-link"></i> Lien de la session
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <a href="<?= APP_URL ?>/?url=conversation&user_id=<?= $d['apprenant_id'] ?? 0 ?>" class="btn-message w-9 h-9 flex items-center justify-center tooltip">
                            <i class="fa-regular fa-message"></i>
                            <span class="tooltip-text">Envoyer un message</span>
                        </a>
                        
                        <?php if (($d['statut'] ?? '') === 'confirmee'): ?>
                            <span class="badge-confirmed"><i class="fa-solid fa-check-circle"></i> Confirmée</span>
                        <?php elseif (($d['statut'] ?? '') === 'refusee'): ?>
                            <span class="badge-rejected"><i class="fa-solid fa-times-circle"></i> Refusée</span>
                        <?php elseif (($d['statut'] ?? '') === 'annulee'): ?>
                            <span class="badge-cancelled"><i class="fa-solid fa-ban"></i> Annulée</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
    
</main>

<script>
// ==================== MODALS ====================
function openModalRefus(sessionId) {
    document.getElementById('refus-session-id').value = sessionId;
    document.getElementById('modal-refus').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModalRefus() {
    document.getElementById('modal-refus').classList.remove('open');
    document.body.style.overflow = '';
}

function closeModalRefusOutside(e) {
    if (e.target === document.getElementById('modal-refus')) closeModalRefus();
}

function openModalLien(sessionId) {
    document.getElementById('lien-session-id').value = sessionId;
    document.getElementById('modal-lien').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModalLien() {
    document.getElementById('modal-lien').classList.remove('open');
    document.body.style.overflow = '';
}

function closeModalLienOutside(e) {
    if (e.target === document.getElementById('modal-lien')) closeModalLien();
}

// ==================== ESCAPE KEY ====================
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModalRefus(); closeModalLien(); }
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>