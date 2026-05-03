<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($mentor['prenom']) ?> <?= e($mentor['nom']) ?> — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<!-- CONTENU PRINCIPAL -->
<main class="flex-1 p-8 max-w-5xl">

    <!-- Fil d'ariane -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="<?= APP_URL ?>/?url=recherche" class="hover:text-violet transition-colors">
            ← Retour a la recherche
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- =====================================================
             COLONNE GAUCHE — Profil
        ====================================================== -->
        <div class="lg:col-span-1 space-y-5">

            <!-- Carte profil -->
            <div class="card p-6 text-center">
                <?php if (!empty($mentor['photo'])): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($mentor['photo']) ?>"
                         class="w-24 h-24 rounded-2xl object-cover mx-auto mb-4" alt="">
                <?php else: ?>
                    <div class="w-24 h-24 rounded-2xl bg-violet flex items-center justify-center
                                text-white font-bold text-3xl mx-auto mb-4">
                        <?= strtoupper(substr($mentor['prenom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <h1 class="font-syne text-xl font-bold text-gray-900">
                    <?= e($mentor['prenom']) ?> <?= e($mentor['nom']) ?>
                </h1>

                <?php if (!empty($mentor['niveau'])): ?>
                <p class="text-sm text-gray-400 mt-1"><?= e($mentor['niveau']) ?></p>
                <?php endif; ?>

                <!-- Note globale -->
                <?php
                $note  = round((float)($mentor['note_moyenne'] ?? 0), 1);
                $plein = floor($note);
                $demi  = ($note - $plein) >= 0.5;
                ?>
                <div class="flex items-center justify-center gap-1.5 mt-3">
                    <div class="flex gap-0.5">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $plein): ?>
                                <span class="text-amber-400">★</span>
                            <?php elseif ($i == $plein + 1 && $demi): ?>
                                <span class="text-amber-300">★</span>
                            <?php else: ?>
                                <span class="text-gray-200">★</span>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="font-semibold text-gray-800">
                        <?= $note > 0 ? number_format($note, 1) : '—' ?>
                    </span>
                    <span class="text-sm text-gray-400">
                        (<?= (int)$mentor['nb_evaluations'] ?> avis)
                    </span>
                </div>

                <!-- ── BOUTONS ACTION ──────────────────────── -->
                <div class="flex gap-2 mt-5">
                    <!-- Envoyer un message -->
                    <a href="<?= APP_URL ?>/?url=conversation&avec=<?= $mentor['id'] ?>"
                       class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5
                              rounded-xl border border-gray-200 hover:border-violet-300
                              hover:bg-violet-50 text-sm font-semibold text-gray-700
                              transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8
                                   a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042
                                   3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Message
                    </a>

                    <!-- Statut dispo -->
                    <?php
                    $statut_cfg = [
                        'disponible' => ['label' => '● Disponible', 'class' => 'text-teal-600 bg-teal-50 border-teal-200'],
                        'occupe'     => ['label' => '● Occupé',      'class' => 'text-amber-600 bg-amber-50 border-amber-200'],
                        'inactif'    => ['label' => '● Inactif',     'class' => 'text-gray-500 bg-gray-100 border-gray-200'],
                    ];
                    $cfg = $statut_cfg[$mentor['statut_dispo'] ?? 'inactif'] ?? $statut_cfg['inactif'];
                    ?>
                    <span class="flex-1 flex items-center justify-center px-3 py-2.5 rounded-xl
                                 border text-xs font-semibold <?= $cfg['class'] ?>">
                        <?= $cfg['label'] ?>
                    </span>
                </div>

                <!-- Bio -->
                <?php if (!empty($mentor['bio'])): ?>
                <p class="text-sm text-gray-500 mt-4 text-left leading-relaxed">
                    <?= nl2br(e($mentor['bio'])) ?>
                </p>
                <?php endif; ?>

                <!-- Expérience -->
                <?php if (!empty($mentor['experience'])): ?>
                <div class="mt-4 p-3 bg-teal-50 rounded-xl text-left">
                    <p class="text-xs font-medium text-teal-700 uppercase tracking-wide mb-1">Experience</p>
                    <p class="text-sm text-teal-800 leading-relaxed">
                        <?= nl2br(e($mentor['experience'])) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Matières enseignées -->
            <?php if (!empty($matieres)): ?>
            <div class="card p-5">
                <h2 class="font-syne font-bold text-gray-900 text-sm uppercase tracking-wide mb-3">
                    Matieres enseignees
                </h2>
                <?php
                $par_cat = [];
                foreach ($matieres as $mat) {
                    $par_cat[$mat['categorie']][] = $mat['nom'];
                }
                foreach ($par_cat as $cat => $noms):
                ?>
                <div class="mb-3 last:mb-0">
                    <p class="text-xs text-gray-400 mb-1.5"><?= e($cat) ?></p>
                    <div class="flex flex-wrap gap-1.5">
                        <?php foreach ($noms as $nom): ?>
                        <span class="px-2.5 py-1 bg-violet-50 text-violet-700 text-xs
                                     font-medium rounded-lg">
                            <?= e($nom) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>

        <!-- =====================================================
             COLONNE DROITE — Disponibilités + Avis
        ====================================================== -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Créneaux disponibles -->
            <div class="card p-6">
                <h2 class="font-syne text-lg font-bold text-gray-900 mb-4">
                    Creneaux disponibles
                </h2>

                <?php if (empty($disponibilites)): ?>
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center
                                justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        Aucun creneau disponible pour le moment.
                    </p>
                    <!-- Proposer d'envoyer un message si pas de créneau -->
                    <a href="<?= APP_URL ?>/?url=conversation&avec=<?= $mentor['id'] ?>"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                              bg-violet-600 hover:bg-violet-700 text-white text-sm
                              font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8
                                   a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042
                                   3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Contacter le mentor
                    </a>
                </div>

                <?php else: ?>

                <?php
                $par_date = [];
                foreach ($disponibilites as $dispo) {
                    $par_date[$dispo['date_dispo']][] = $dispo;
                }
                $jours_fr = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi',
                             'Thursday'=>'Jeudi','Friday'=>'Vendredi',
                             'Saturday'=>'Samedi','Sunday'=>'Dimanche'];
                $mois_fr  = ['January'=>'jan','February'=>'fev','March'=>'mars',
                             'April'=>'avr','May'=>'mai','June'=>'juin','July'=>'juil',
                             'August'=>'aout','September'=>'sep','October'=>'oct',
                             'November'=>'nov','December'=>'dec'];
                ?>

                <div class="space-y-4">
                    <?php foreach ($par_date as $date => $creneaux): ?>
                    <?php
                        $ts     = strtotime($date);
                        $jour   = $jours_fr[date('l', $ts)] ?? date('l', $ts);
                        $libDate= $jour . ' ' . date('d', $ts) . ' ' . ($mois_fr[date('F', $ts)] ?? date('M', $ts));
                    ?>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                            <?= $libDate ?>
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($creneaux as $d): ?>
                            <a href="<?= APP_URL ?>/?url=reserver&dispo_id=<?= $d['id'] ?>&mentor_id=<?= $mentor['id'] ?>"
                               class="flex items-center justify-between p-3 rounded-xl border
                                      border-gray-200 hover:border-violet-400 hover:bg-violet-50
                                      transition-all group cursor-pointer">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">
                                        <?= date('H:i', strtotime($d['heure_debut'])) ?>
                                        <span class="text-gray-400 font-normal">→</span>
                                        <?= date('H:i', strtotime($d['heure_fin'])) ?>
                                    </p>
                                    <?php if (!empty($d['matiere_nom'])): ?>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <?= e($d['matiere_nom']) ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-2">
                                    <?php if ($d['mode_session'] === 'en_ligne'): ?>
                                    <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs
                                                 font-medium rounded-md">En ligne</span>
                                    <?php else: ?>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs
                                                 font-medium rounded-md">Presentiel</span>
                                    <?php endif; ?>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-violet
                                                transition-colors"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Avis des apprenants -->
            <div class="card p-6">
                <h2 class="font-syne text-lg font-bold text-gray-900 mb-4">
                    Avis des apprenants
                    <span class="text-sm font-normal text-gray-400 ml-2">
                        (<?= count($evaluations) ?>)
                    </span>
                </h2>

                <?php if (empty($evaluations)): ?>
                <p class="text-gray-400 text-sm text-center py-6">
                    Aucun avis pour le moment.
                </p>

                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($evaluations as $ev): ?>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <?php if (!empty($ev['photo'])): ?>
                                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($ev['photo']) ?>"
                                         class="w-8 h-8 rounded-full object-cover" alt="">
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center
                                                justify-center text-white text-xs font-bold">
                                        <?= strtoupper(substr($ev['prenom'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        <?= e($ev['prenom']) ?> <?= e(substr($ev['nom'], 0, 1)) ?>.
                                    </p>
                                    <?php if (!empty($ev['matiere_nom'])): ?>
                                    <p class="text-xs text-gray-400"><?= e($ev['matiere_nom']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="flex gap-0.5 justify-end">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-xs <?= $i <= $ev['note'] ? 'text-amber-400' : 'text-gray-200' ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <?= date('d/m/Y', strtotime($ev['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php if (!empty($ev['commentaire'])): ?>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?= nl2br(e($ev['commentaire'])) ?>
                        </p>
                        <?php endif; ?>
                        <?php if (!empty($ev['reponse_mentor'])): ?>
                        <div class="mt-3 pl-3 border-l-2 border-teal-300">
                            <p class="text-xs font-medium text-teal-700 mb-1">Reponse du mentor</p>
                            <p class="text-sm text-gray-600">
                                <?= nl2br(e($ev['reponse_mentor'])) ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>
</div>

</body>
</html>