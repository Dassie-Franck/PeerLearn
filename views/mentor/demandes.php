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
        
        /* ==================== MODALS ==================== */
        #modal-refus, #modal-lien {
            position: fixed; inset: 0;
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(6px);
            z-index: 100;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease;
        }
        #modal-refus.open, #modal-lien.open { opacity: 1; pointer-events: all; }
        
        .modal-box {
            background: #fff;
            border-radius: 24px;
            width: 100%;
            max-width: 440px;
            margin: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transform: translateY(12px) scale(0.98);
            transition: transform 0.2s ease;
            overflow: hidden;
        }
        .open .modal-box { transform: translateY(0) scale(1); }
        
        /* ==================== CARDS ==================== */
        .demand-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .demand-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .demand-card-pending {
            background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
            border-color: #FDE68A;
        }
        
        .badge-pending {
            background: #FEF3C7;
            color: #D97706;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-confirmed {
            background: #E8F5E9;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-rejected {
            background: #FFEBEE;
            color: #C62828;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-cancelled {
            background: #F5F5F5;
            color: #616161;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .btn-confirm {
            background: linear-gradient(135deg, #0FC4A7, #0D9488);
            color: #fff;
            padding: 8px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15,196,167,0.3);
        }
        
        .btn-reject {
            background: #FEF2F2;
            color: #EF4444;
            border: 1px solid #FEE2E2;
            padding: 8px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-reject:hover {
            background: #FEE2E2;
            transform: translateY(-2px);
        }
        
        .btn-message {
            background: rgba(15,196,167,0.1);
            color: #0FC4A7;
            padding: 8px;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .btn-message:hover {
            background: #0FC4A7;
            color: #fff;
            transform: translateY(-2px);
        }
        
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
        
        /* Stats counters */
        .stat-counter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .input-field {
            width: 100%;
            padding: 12px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #0FC4A7;
            box-shadow: 0 0 0 3px rgba(15,196,167,0.1);
        }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<!-- ==================== MODAL REFUS ==================== -->
<div id="modal-refus" onclick="closeModalRefusOutside(event)">
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
<div id="modal-lien" onclick="closeModalLienOutside(event)">
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
<main style="padding: 32px;">
    
    <!-- En-tête -->
    <div class="animate-fadeInUp" style="margin-bottom: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                    <i class="fa-solid fa-clock" style="color: #0FC4A7; margin-right: 12px;"></i>
                    Demandes reçues
                </h1>
                <p style="color: #64748B; font-size: 14px;">Gérez les demandes de réservation de vos créneaux.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <?php if ($compteurs['en_attente'] > 0): ?>
                <span class="stat-counter" style="background: #FEF3C7; color: #D97706;">
                    <span class=""></span>
                    <?= $compteurs['en_attente'] ?> en attente
                </span>
                <?php endif; ?>
                <?php if ($compteurs['confirmee'] > 0): ?>
                <span class="stat-counter" style="background: #E8F5E9; color: #2E7D32;">
                    <span class=""></span>
                    <?= $compteurs['confirmee'] ?> confirmée<?= $compteurs['confirmee'] > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>
                <?php if ($compteurs['refusee'] > 0): ?>
                <span class="stat-counter" style="background: #FFEBEE; color: #C62828;">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <?= $compteurs['refusee'] ?> refusée<?= $compteurs['refusee'] > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($demandes)): ?>
    <!-- État vide -->
    <div class="animate-fadeInUp empty-state" style="background: #fff; border-radius: 24px; border: 1px solid #E2E8F0;">
        <div class="empty-icon">
            <i class="fa-regular fa-bell-slash text-3xl text-gray-400"></i>
        </div>
        <p style="font-size: 16px; font-weight: 600; color: #0F172A; margin-bottom: 8px;">Aucune demande reçue</p>
        <p style="color: #64748B; font-size: 14px; margin-bottom: 24px;">Les étudiants qui réservent tes créneaux apparaîtront ici.</p>
        <a href="<?= APP_URL ?>/?url=disponibilites" class="btn-confirm" style="display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-calendar-plus"></i> Gérer mes disponibilités
        </a>
    </div>
    
    <?php else: 
        $en_attente = array_filter($demandes, fn($d) => $d['statut'] === 'en_attente');
        $autres = array_filter($demandes, fn($d) => $d['statut'] !== 'en_attente');
    ?>
    
    <!-- Demandes en attente -->
    <?php if (!empty($en_attente)): ?>
    <div class="animate-fadeInUp" style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A;">
              
                En attente
            </h2>
            <span style="background: #FEF3C7; color: #D97706; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><?= count($en_attente) ?></span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($en_attente as $d): ?>
            <div class="demand-card demand-card-pending">
                <div style="display: flex; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        <?= strtoupper(substr($d['apprenant_prenom'], 0, 1)) ?>
                    </div>
                    
                    <!-- Infos -->
                    <div style="flex: 1; min-width: 200px;">
                        <p style="font-weight: 700; color: #0F172A; margin-bottom: 4px;">
                            <?= e($d['apprenant_nom_complet']) ?>
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 8px;">
                            <span style="font-size: 12px; color: #0FC4A7; background: rgba(15,196,167,0.1); padding: 4px 10px; border-radius: 20px;">
                                <i class="fa-solid fa-book"></i> <?= e($d['matiere_nom']) ?>
                            </span>
                            <span style="font-size: 12px; color: #64748B;">
                                <i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($d['date_session'])) ?>
                            </span>
                            <span style="font-size: 12px; color: #64748B;">
                                <i class="fa-regular fa-clock"></i> <?= date('H:i', strtotime($d['heure_debut'])) ?> — <?= date('H:i', strtotime($d['heure_fin'])) ?>
                            </span>
                            <span style="font-size: 12px; color: <?= $d['mode_session'] === 'en_ligne' ? '#0FC4A7' : '#F59E0B' ?>;">
                                <i class="fa-solid fa-<?= $d['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                <?= $d['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                            </span>
                        </div>
                        <p style="font-size: 11px; color: #94A3B8;">
                            <i class="fa-regular fa-clock"></i> Reçue le <?= date('d/m/Y à H:i', strtotime($d['created_at'])) ?>
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div style="display: flex; gap: 10px; flex-shrink: 0;">
                        <a href="<?= APP_URL ?>/?url=conversation&user_id=<?= $d['apprenant_id'] ?? 0 ?>" class="btn-message tooltip" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;">
                            <i class="fa-regular fa-message"></i>
                            <span class="tooltip-text">Envoyer un message</span>
                        </a>
                        
                        <?php if (($d['mode_session'] ?? '') === 'en_ligne'): ?>
                        <button onclick="openModalLien(<?= $d['id'] ?? 0 ?>)" class="btn-confirm" style="padding: 8px 18px;">
                            <i class="fa-solid fa-check-circle"></i> Confirmer
                        </button>
                        <?php else: ?>
                        <form method="POST" action="<?= APP_URL ?>/?url=confirmer" style="margin: 0;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="session_id" value="<?= $d['id'] ?? 0 ?>">
                            <input type="hidden" name="lien_session" value="">
                            <button type="submit" class="btn-confirm" style="padding: 8px 18px;">
                                <i class="fa-solid fa-check-circle"></i> Confirmer
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <button onclick="openModalRefus(<?= $d['id'] ?? 0 ?>)" class="btn-reject" style="padding: 8px 18px;">
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
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #64748B; margin-right: 8px;"></i>
                Historique
            </h2>
            <span style="background: #F1F5F9; color: #64748B; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><?= count($autres) ?></span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <?php foreach ($autres as $d): ?>
            <div class="demand-card" style="padding: 16px 20px;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <!-- Date -->
                    <div style="background: #F8FAFC; border-radius: 16px; padding: 8px 12px; text-align: center; min-width: 70px;">
                        <p style="font-size: 11px; font-weight: 600; color: #0FC4A7; text-transform: uppercase;">
                            <?= strtoupper(date('M', strtotime($d['date_session']))) ?>
                        </p>
                        <p style="font-size: 20px; font-weight: 800; color: #0F172A;">
                            <?= date('d', strtotime($d['date_session'])) ?>
                        </p>
                    </div>
                    
                    <!-- Infos -->
                    <div style="flex: 1;">
                        <p style="font-weight: 600; color: #0F172A; margin-bottom: 4px;">
                            <?= e($d['apprenant_nom_complet']) ?>
                            <span style="color: #94A3B8; font-weight: 400;">·</span>
                            <?= e($d['matiere_nom']) ?>
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                            <p style="font-size: 12px; color: #64748B;">
                                <i class="fa-regular fa-clock"></i> <?= date('H:i', strtotime($d['heure_debut'])) ?> — <?= date('H:i', strtotime($d['heure_fin'])) ?>
                            </p>
                            <p style="font-size: 12px; color: #64748B;">
                                <i class="fa-solid fa-<?= $d['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                <?= $d['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                            </p>
                        </div>
                        <?php if ($d['statut'] === 'confirmee' && !empty($d['lien_session'])): ?>
                        <a href="<?= e($d['lien_session']) ?>" target="_blank" style="font-size: 11px; color: #0FC4A7; text-decoration: none; margin-top: 6px; display: inline-block;">
                            <i class="fa-solid fa-link"></i> Lien de la session
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Actions -->
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <a href="<?= APP_URL ?>/?url=conversation&user_id=<?= $d['apprenant_id'] ?? 0 ?>" class="btn-message tooltip" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;">
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

</div><!-- ferme main-content-wrapper -->

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

// ==================== TOOLTIPS ====================
const tooltipStyle = document.createElement('style');
tooltipStyle.textContent = `
    .tooltip { position: relative; }
    .tooltip .tooltip-text {
        visibility: hidden;
        background-color: #1E293B;
        color: #fff;
        text-align: center;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        position: absolute;
        z-index: 10;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
`;
document.head.appendChild(tooltipStyle);

// ==================== ESCAPE KEY ====================
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModalRefus(); closeModalLien(); }
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>