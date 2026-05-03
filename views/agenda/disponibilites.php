<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes disponibilites — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        /* ── Modal ── */
        #modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.45);
            backdrop-filter: blur(4px);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }
        #modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        #modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            margin: 16px;
            box-shadow: 0 24px 64px rgba(0,0,0,.18);
            transform: translateY(16px) scale(.98);
            transition: transform .25s ease, opacity .25s ease;
            overflow: hidden;
        }
        #modal-overlay.open #modal-box {
            transform: translateY(0) scale(1);
        }

        /* ── Mode radio cards ── */
        .mode-label {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            cursor: pointer;
            transition: all .2s;
        }
        .mode-label.selected {
            border-color: #0FC4A7;
            background: rgba(15,196,167,.07);
        }

        /* ── Creneau card ── */
        .creneau-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid #E9EBF0;
            background: #fff;
            transition: box-shadow .2s, border-color .2s;
        }
        .creneau-card:hover {
            border-color: #C7D2FE;
            box-shadow: 0 4px 12px rgba(91,79,232,.08);
        }
        .creneau-card.reserved {
            border-color: #FDE68A;
            background: #FFFBEB;
        }

        /* ── Date pill ── */
        .date-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #F5F6FA;
            border: 1px solid #E9EBF0;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
        }

        /* ── Trash btn ── */
        .btn-trash {
            background: none;
            border: none;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            color: #D1D5DB;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
            flex-shrink: 0;
        }
        .btn-trash:hover {
            background: #FEF2F2;
            color: #EF4444;
        }

        /* ── Stat mini ── */
        .stat-mini {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #E9EBF0;
            background: #F9FAFB;
            font-size: 13px;
            color: #374151;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<!-- ══════════════════════════════════════
     MODAL — NOUVEAU CRENEAU
══════════════════════════════════════ -->
<div id="modal-overlay" onclick="closeModalOutside(event)">
    <div id="modal-box">

        <!-- Header modal -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="font-syne text-lg font-bold text-gray-900">Nouveau creneau</h2>
                <p class="text-xs text-gray-400 mt-0.5">Remplis les champs pour publier une disponibilite.</p>
            </div>
            <button onclick="closeModal()"
                    class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center
                           justify-center text-gray-500 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Body modal -->
        <div class="px-6 py-5">
            <form method="POST" action="<?= APP_URL ?>/?url=ajouter-dispo" id="form-dispo">
                <?= csrf_field() ?>

                <!-- Matiere -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fa-solid fa-book-open text-teal-500 mr-1.5"></i>
                        Matiere <span class="text-red-500">*</span>
                    </label>
                    <select name="matiere_id" class="input-field" required>
                        <option value="">Selectionner une matiere</option>
                        <?php foreach ($matieres_mentor as $mat): ?>
                        <option value="<?= $mat['id'] ?>"><?= e($mat['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($matieres_mentor)): ?>
                    <div class="mt-2 p-3 bg-orange-50 border border-orange-200 rounded-lg
                                text-xs text-orange-800 flex gap-2 items-start">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
                        <span>
                            Aucune matiere trouvee.
                            <a href="<?= APP_URL ?>/?url=mentor-profil"
                               class="font-semibold underline">Ajoute-en dans ton profil →</a>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Date -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fa-solid fa-calendar text-teal-500 mr-1.5"></i>
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_dispo"
                           min="<?= date('Y-m-d') ?>"
                           class="input-field" required>
                </div>

                <!-- Horaires -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="form-label">
                            <i class="fa-regular fa-clock text-teal-500 mr-1.5"></i>
                            Debut <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="heure_debut" id="heure_debut"
                               class="input-field" required>
                    </div>
                    <div>
                        <label class="form-label">
                            <i class="fa-regular fa-clock text-gray-400 mr-1.5"></i>
                            Fin <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="heure_fin" id="heure_fin"
                               class="input-field" required>
                    </div>
                </div>

                <!-- Mode session -->
                <div class="mb-6">
                    <label class="form-label">
                        <i class="fa-solid fa-laptop text-teal-500 mr-1.5"></i>
                        Mode de session
                    </label>
                    <div class="flex gap-3">
                        <label class="mode-label selected" id="lbl-online" onclick="selectMode('en_ligne')">
                            <input type="radio" name="mode_session" value="en_ligne"
                                   id="r-online" checked style="display:none">
                            <i class="fa-solid fa-wifi text-teal-500"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 m-0">En ligne</p>
                                <p class="text-xs text-gray-400 m-0">Zoom / Meet</p>
                            </div>
                        </label>
                        <label class="mode-label" id="lbl-offline" onclick="selectMode('presentiel')">
                            <input type="radio" name="mode_session" value="presentiel"
                                   id="r-offline" style="display:none">
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 m-0">Presentiel</p>
                                <p class="text-xs text-gray-400 m-0">Sur site</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl
                               bg-teal-500 hover:bg-teal-600 text-white font-semibold text-sm
                               transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    Publier ce creneau
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     CONTENU PRINCIPAL
══════════════════════════════════════ -->
<main class="flex-1 p-8">

    <!-- En-tete page -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-syne text-2xl font-bold text-gray-900">
                Mes disponibilites
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Publie tes creneaux pour que les etudiants puissent te reserver.
            </p>
        </div>

        <!-- Stats + bouton -->
        <div class="flex items-center gap-3 flex-wrap">
            <?php
            $total    = count($disponibilites);
            $libres   = count(array_filter($disponibilites, fn($d) => !$d['est_reservee']));
            $reserves = $total - $libres;
            ?>
            <span class="stat-mini">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-400 flex-shrink-0"></span>
                <strong><?= $libres ?></strong> libre<?= $libres > 1 ? 's' : '' ?>
            </span>
            <span class="stat-mini">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 flex-shrink-0"></span>
                <strong><?= $reserves ?></strong> reserve<?= $reserves > 1 ? 's' : '' ?>
            </span>
            <button onclick="openModal()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500
                           hover:bg-teal-600 text-white text-sm font-semibold transition-colors">
                <i class="fa-solid fa-plus"></i>
                Nouveau creneau
            </button>
        </div>
    </div>

    <!-- ── LISTE VIDE ── -->
    <?php if (empty($disponibilites)): ?>
    <div class="card">
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-regular fa-calendar-xmark text-3xl text-gray-300"></i>
            </div>
            <h3 class="font-syne text-base font-bold text-gray-700 mb-2">Aucun creneau publie</h3>
            <p class="text-sm text-gray-400 max-w-xs mx-auto mb-6 leading-relaxed">
                Clique sur le bouton ci-dessus pour publier tes premieres disponibilites.
                Les etudiants pourront les reserver.
            </p>
            <button onclick="openModal()" class="btn-primary">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter un creneau
            </button>
        </div>
    </div>

    <?php else: ?>

    <!-- ── LISTE DES CRENEAUX ── -->
    <?php
    $par_date = [];
    foreach ($disponibilites as $d) {
        $par_date[$d['date_dispo']][] = $d;
    }
    $jours = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi',
              'Wednesday'=>'Mercredi','Thursday'=>'Jeudi',
              'Friday'=>'Vendredi','Saturday'=>'Samedi'];
    $mois  = ['January'=>'janvier','February'=>'février','March'=>'mars',
              'April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet',
              'August'=>'août','September'=>'septembre','October'=>'octobre',
              'November'=>'novembre','December'=>'décembre'];
    ?>

    <div class="space-y-8">
        <?php foreach ($par_date as $date => $creneaux):
            $ts      = strtotime($date);
            $jour    = $jours[date('l', $ts)];
            $moi     = $mois[date('F', $ts)];
            $est_auj = ($date === date('Y-m-d'));
            $est_dem = ($date === date('Y-m-d', strtotime('+1 day')));
        ?>
        <div>
            <!-- En-tete date -->
            <div class="flex items-center gap-3 mb-3">
                <div class="date-pill">
                    <i class="" style="font-size:11px"></i>
                    <?php if ($est_auj): ?>
                        <span class="text-teal-500">Aujourd hui</span>
                        · <?= date('d', $ts) ?> <?= $moi ?>
                    <?php elseif ($est_dem): ?>
                        <span class="text-violet-500">Demain</span>
                        · <?= date('d', $ts) ?> <?= $moi ?>
                    <?php else: ?>
                        <?= $jour ?> <?= date('d', $ts) ?> <?= $moi ?> <?= date('Y', $ts) ?>
                    <?php endif; ?>
                </div>
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400 whitespace-nowrap">
                    <?= count($creneaux) ?> creneau<?= count($creneaux) > 1 ? 'x' : '' ?>
                </span>
            </div>

            <!-- Creneaux du jour -->
            <div class="space-y-2">
                <?php foreach ($creneaux as $c): ?>
                <div class="creneau-card <?= $c['est_reservee'] ? 'reserved' : '' ?>">

                    <!-- Dot couleur -->
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                          style="background:<?= $c['est_reservee'] ? '#F59E0B' : '#0FC4A7' ?>">
                    </span>

                    <!-- Horaire -->
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <i class="fa-regular fa-clock text-gray-300 text-xs"></i>
                        <span class="font-syne font-bold text-gray-900 text-sm">
                            <?= date('H:i', strtotime($c['heure_debut'])) ?>
                        </span>
                        <span class="text-gray-300 text-xs mx-1">→</span>
                        <span class="font-syne font-bold text-gray-900 text-sm">
                            <?= date('H:i', strtotime($c['heure_fin'])) ?>
                        </span>
                    </div>

                    <!-- Separateur vertical -->
                    <div class="w-px h-8 bg-gray-100 flex-shrink-0"></div>

                    <!-- Matiere + mode -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate mb-0.5">
                            <?= e($c['matiere_nom']) ?>
                        </p>
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <?php if ($c['mode_session'] === 'en_ligne'): ?>
                                <i class="fa-solid fa-wifi" style="font-size:10px"></i> En ligne
                            <?php else: ?>
                                <i class="fa-solid fa-location-dot" style="font-size:10px"></i> Presentiel
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Statut / action -->
                    <?php if ($c['est_reservee']): ?>
                        <span class="badge-warning flex-shrink-0">
                            <i class="fa-solid fa-lock mr-1 text-xs"></i>Reserve
                        </span>
                    <?php else: ?>
                        <span class="badge-success flex-shrink-0">
                            <i class="fa-solid fa-circle-check mr-1 text-xs"></i>Libre
                        </span>
                        <form method="POST" action="<?= APP_URL ?>/?url=suppr-dispo"
                              onsubmit="return confirm('Supprimer ce creneau ?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="dispo_id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn-trash" title="Supprimer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</main>
</div><!-- Ferme .ml-64 ouvert dans navbar_mentor.php -->

<!-- ══════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════ -->
<script>
function openModal() {
    document.getElementById('modal-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
}
function closeModalOutside(e) {
    if (e.target === document.getElementById('modal-overlay')) closeModal();
}

// Fermer avec Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});

// Sélection mode session
function selectMode(mode) {
    const lOn  = document.getElementById('lbl-online');
    const lOff = document.getElementById('lbl-offline');
    const rOn  = document.getElementById('r-online');
    const rOff = document.getElementById('r-offline');
    if (mode === 'en_ligne') {
        lOn.classList.add('selected');
        lOff.classList.remove('selected');
        rOn.checked = true;
    } else {
        lOff.classList.add('selected');
        lOn.classList.remove('selected');
        rOff.checked = true;
    }
}

// Validation heure fin > heure debut
document.getElementById('form-dispo').addEventListener('submit', function(e) {
    const debut = document.getElementById('heure_debut').value;
    const fin   = document.getElementById('heure_fin').value;
    if (debut && fin && fin <= debut) {
        e.preventDefault();
        window.Toast && Toast.error('L heure de fin doit etre apres l heure de debut.');
    }
});
</script>

</body>
</html>