<?php
// ============================================================
//  views/agenda/mes_sessions.php
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        * { font-family: 'Inter', sans-serif; }

        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .filter-btn {
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #E2E8F0;
            background: #fff;
            color: #64748B;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .filter-btn:hover { border-color: #5B4FE8; color: #5B4FE8; }
        .filter-btn.active { background: #5B4FE8; border-color: #5B4FE8; color: #fff; }

        .session-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .session-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }

        .session-date-box {
            background: #F8FAFC;
            border-radius: 16px;
            padding: 12px 16px;
            text-align: center;
            min-width: 80px;
        }

        .badge-pending { background: #FEF3C7; color: #D97706; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .badge-confirmed { background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .badge-completed { background: #E3F2FD; color: #1565C0; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .badge-cancelled { background: #FFEBEE; color: #C62828; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }

        .btn-evaluate { background: linear-gradient(135deg, #5B4FE8, #7C3AED); color: #fff; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block; text-align: center; }
        .btn-evaluate:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(91,79,232,0.3); }

        .btn-cancel { background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 600; cursor: pointer; width: 100%; }
        .btn-cancel:hover { background: #FEE2E2; transform: translateY(-2px); }

        .btn-message { background: #F1F5F9; color: #64748B; padding: 8px 16px; border-radius: 12px; font-size: 12px; font-weight: 600; text-decoration: none; text-align: center; display: block; }
        .btn-message:hover { background: #E2E8F0; color: #1E293B; }

        .empty-state { text-align: center; padding: 60px 20px; background: #fff; border-radius: 24px; border: 1px solid #E2E8F0; }
        .empty-icon { width: 80px; height: 80px; background: #F1F5F9; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }

        .flash-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; padding: 14px 18px; border-radius: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .flash-error { background: #FEF2F2; border: 1px solid #FEE2E2; color: #991B1B; padding: 14px 18px; border-radius: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

        .modal { position: fixed; inset: 0; background: rgba(17,24,39,0.7); backdrop-filter: blur(6px); z-index: 100; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s ease; }
        .modal.open { opacity: 1; pointer-events: all; }
        .modal-box { background: #fff; border-radius: 24px; width: 100%; max-width: 440px; margin: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: translateY(12px) scale(0.98); transition: transform 0.2s ease; overflow: hidden; }
        .open .modal-box { transform: translateY(0) scale(1); }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-textarea { width: 100%; padding: 12px; border-radius: 14px; border: 1px solid #E2E8F0; font-size: 14px; resize: vertical; }
        .form-textarea:focus { outline: none; border-color: #5B4FE8; box-shadow: 0 0 0 3px rgba(91,79,232,0.1); }

        .role-badge { background: #F1F5F9; color: #64748B; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; display: inline-flex; align-items: center; gap: 4px; }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<!-- CONTENU PRINCIPAL -->
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
            <a href="<?= APP_URL ?>/?url=recherche" class="btn-evaluate inline-flex items-center gap-2 px-6 py-2.5">
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
            ''          => ['label' => 'Toutes', 'icon' => 'fa-star'],
            'en_attente'=> ['label' => 'En attente', 'icon' => 'fa-clock'],
            'confirmee' => ['label' => 'Confirmées', 'icon' => 'fa-check-circle'],
            'terminee'  => ['label' => 'Terminées', 'icon' => 'fa-circle-check'],
            'annulee'   => ['label' => 'Annulées', 'icon' => 'fa-ban'],
        ];
        foreach ($filtres as $val => $info):
        ?>
        <a href="<?= APP_URL ?>/?url=mes-sessions<?= $val ? '&statut=' . $val : '' ?>"
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
        <a href="<?= APP_URL ?>/?url=recherche" class="btn-evaluate inline-flex items-center gap-2">
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

            //  Récupérer la valeur de deja_evalue (ajoutée dans le controller)
            $deja_evalue = $s['deja_evalue'] ?? false;

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
                    <?php if ($s['statut'] === 'terminee' && !$est_mentor && !$deja_evalue): ?>
                    <a href="<?= APP_URL ?>/evaluer&session_id=<?= $s['id'] ?>" class="btn-evaluate text-center">
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
            <!--  CORRECTION : URL correcte pour l'annulation -->
            <form method="POST" action="<?= APP_URL ?>/?url=annuler">
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
function ouvrirModalAnnulation(sessionId) {
    document.getElementById('modal-session-id').value = sessionId;
    document.getElementById('modal-annulation').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function fermerModal() {
    document.getElementById('modal-annulation').classList.remove('open');
    document.body.style.overflow = '';
}

document.getElementById('modal-annulation').addEventListener('click', function(e) {
    if (e.target === this) fermerModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') fermerModal();
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>
