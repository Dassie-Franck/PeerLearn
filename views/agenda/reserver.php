<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une session — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<main class="flex-1 p-8">

    <!-- Fil d'ariane -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="<?= APP_URL ?>/?url=recherche" class="hover:text-violet transition-colors">Mentors</a>
        <span>›</span>
        <a href="<?= APP_URL ?>/?url=fiche-mentor&id=<?= $mentor['id'] ?>"
           class="hover:text-violet transition-colors">
            <?= e($mentor['prenom']) ?> <?= e($mentor['nom']) ?>
        </a>
        <span>›</span>
        <span class="text-gray-600">Réserver</span>
    </div>

    <div class="max-w-xl mx-auto">

        <!-- ── Récapitulatif du créneau ── -->
        <div class="card p-6 mb-6">
            <h1 class="font-syne text-xl font-bold text-gray-900 mb-5">
                Confirmer la réservation
            </h1>

            <!-- Mentor -->
            <div class="flex items-center gap-4 pb-5 border-b border-gray-100 mb-5">
                <?php if (!empty($mentor['photo'])): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($mentor['photo']) ?>"
                         class="w-14 h-14 rounded-2xl object-cover" alt="">
                <?php else: ?>
                    <div class="w-14 h-14 rounded-2xl bg-violet flex items-center
                                justify-center text-white font-bold text-xl flex-shrink-0">
                        <?= strtoupper(substr($mentor['prenom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <p class="font-syne font-bold text-gray-900">
                        <?= e($mentor['prenom']) ?> <?= e($mentor['nom']) ?>
                    </p>
                    <?php if (!empty($mentor['niveau'])): ?>
                    <p class="text-sm text-gray-400"><?= e($mentor['niveau']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Détails session -->
            <?php
            $ts      = strtotime($dispo['date_dispo']);
            $jours_fr = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi',
                         'Thursday'=>'Jeudi','Friday'=>'Vendredi',
                         'Saturday'=>'Samedi','Sunday'=>'Dimanche'];
            $mois_fr  = ['January'=>'janvier','February'=>'février','March'=>'mars',
                         'April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet',
                         'August'=>'août','September'=>'septembre','October'=>'octobre',
                         'November'=>'novembre','December'=>'décembre'];
            $jour    = $jours_fr[date('l', $ts)] ?? date('l', $ts);
            $mois    = $mois_fr[date('F', $ts)]  ?? date('F', $ts);
            $libDate = $jour . ' ' . date('d', $ts) . ' ' . $mois . ' ' . date('Y', $ts);
            $debut   = date('H:i', strtotime($dispo['heure_debut']));
            $fin     = date('H:i', strtotime($dispo['heure_fin']));
            $duree   = (strtotime($dispo['heure_fin']) - strtotime($dispo['heure_debut'])) / 60;
            ?>

            <div class="space-y-3">

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                        </svg>
                        Matière
                    </span>
                    <span class="font-medium text-gray-900 text-sm">
                        <?= e($dispo['matiere_nom']) ?>
                    </span>
                </div>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date
                    </span>
                    <span class="font-medium text-gray-900 text-sm"><?= $libDate ?></span>
                </div>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                        Horaire
                    </span>
                    <span class="font-medium text-gray-900 text-sm">
                        <?= $debut ?> – <?= $fin ?>
                        <span class="text-gray-400 font-normal">(<?= $duree ?> min)</span>
                    </span>
                </div>

                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.069A1 1 0 0121 8.868v6.264a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                        </svg>
                        Mode
                    </span>
                    <?php if ($dispo['mode_session'] === 'en_ligne'): ?>
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-lg">
                        En ligne — lien envoyé après confirmation
                    </span>
                    <?php else: ?>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">
                        Présentiel
                    </span>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- ── Info statut ── -->
        <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-100
                    rounded-xl mb-6">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"/>
            </svg>
            <p class="text-sm text-amber-800 leading-relaxed">
                Votre demande sera envoyée au mentor. La session sera confirmée
                uniquement après son accord.
                <?php if ($dispo['mode_session'] === 'en_ligne'): ?>
                Le lien de visioconférence vous sera communiqué après confirmation.
                <?php endif; ?>
            </p>
        </div>

        <!-- ── Formulaire de confirmation ── -->
        <form method="POST"
              action="<?= APP_URL ?>/?url=reserver&dispo_id=<?= $dispo['id'] ?>&mentor_id=<?= $mentor['id'] ?>">
            <?= csrfField() ?>

            <div class="flex gap-3">
                <a href="<?= APP_URL ?>/?url=fiche-mentor&id=<?= $mentor['id'] ?>"
                   class="flex-1 px-5 py-3 rounded-xl border border-gray-200 text-sm
                          text-gray-600 font-medium text-center hover:bg-gray-50 transition-colors">
                    ← Retour
                </a>
                <button type="submit"
                        class="flex-1 px-5 py-3 rounded-xl bg-violet text-white text-sm
                               font-medium hover:bg-violet-700 transition-colors
                               flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    Envoyer la demande
                </button>
            </div>
        </form>

    </div>
</main>
</div>

</body>
</html>