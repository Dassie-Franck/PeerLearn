<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes sessions — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>

<?php if ($est_mentor): ?>
    <?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>
<?php else: ?>
    <?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>
<?php endif; ?>

<!-- MODALE ANNULATION -->
<div id="modal-annuler" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
         onclick="fermerModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div>
                <h3 class="font-syne font-bold text-gray-900">Annuler la session</h3>
                <p class="text-xs text-gray-400" id="annuler-subtitle">—</p>
            </div>
        </div>
        <form method="POST" action="<?= APP_URL ?>/?url=annuler">
            <?= csrfField() ?>
            <input type="hidden" name="session_id" id="annuler-session-id">
            <div class="mb-5">
                <label class="block text-xs font-medium text-gray-600 mb-1.5 uppercase tracking-wide">
                    Motif (optionnel)
                </label>
                <textarea name="motif" rows="3"
                          placeholder="Raison de l'annulation..."
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm
                                 focus:outline-none focus:border-red-300 focus:ring-2
                                 focus:ring-red-50 transition-colors resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="fermerModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200
                               text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                    Retour
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 text-white
                               text-sm font-medium hover:bg-red-600 transition-colors">
                    Confirmer l'annulation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CONTENU -->
<main class="flex-1 p-8">

    <!-- En-tête -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-syne text-2xl font-bold text-gray-900">Mes sessions</h1>
            <p class="text-gray-500 text-sm mt-1">
                <?= count($sessions) ?> session<?= count($sessions) > 1 ? 's' : '' ?> au total
            </p>
        </div>
        <?php if (!$est_mentor): ?>
        <a href="<?= APP_URL ?>/?url=recherche"
           class="btn-primary text-sm px-4 py-2">
            + Trouver un mentor
        </a>
        <?php endif; ?>
    </div>

    <?php if (empty($sessions)): ?>
    <!-- État vide -->
    <div class="card text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-gray-600 font-medium mb-1">Aucune session pour le moment</p>
        <?php if (!$est_mentor): ?>
        <p class="text-gray-400 text-sm mb-5">Trouvez un mentor et réservez votre première session.</p>
        <a href="<?= APP_URL ?>/?url=recherche" class="btn-primary">Trouver un mentor</a>
        <?php endif; ?>
    </div>

    <?php else: ?>

    <?php
    $jours_fr = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi',
                 'Thursday'=>'Jeudi','Friday'=>'Vendredi',
                 'Saturday'=>'Samedi','Sunday'=>'Dimanche'];
    $mois_fr  = ['January'=>'jan','February'=>'fév','March'=>'mars',
                 'April'=>'avr','May'=>'mai','June'=>'juin','July'=>'juil',
                 'August'=>'août','September'=>'sep','October'=>'oct',
                 'November'=>'nov','December'=>'déc'];

    // Helper d'affichage d'une carte session
    $afficher_carte = function(array $sess) use ($user_id, $est_mentor, $deja_evalues, $jours_fr, $mois_fr): void {
        $ts       = strtotime($sess['date_session']);
        $jour     = $jours_fr[date('l',$ts)] ?? date('l',$ts);
        $mois     = $mois_fr[date('F',$ts)]  ?? date('M',$ts);
        $debut    = date('H:i', strtotime($sess['heure_debut']));
        $fin      = date('H:i', strtotime($sess['heure_fin']));
        $enLigne  = $sess['mode_session'] === 'en_ligne';

        // L'interlocuteur vu par l'utilisateur courant
        if ($est_mentor || $sess['mentor_id'] == $user_id) {
            $interlocuteur = $sess['apprenant_nom_complet'];
            $photo         = $sess['apprenant_photo'];
            $role_label    = 'Apprenant';
        } else {
            $interlocuteur = $sess['mentor_nom_complet'];
            $photo         = $sess['mentor_photo'];
            $role_label    = 'Mentor';
        }

        $peut_evaluer = !$est_mentor
            && $sess['statut'] === 'terminee'
            && $sess['apprenant_id'] == $user_id
            && !in_array($sess['id'], $deja_evalues);

        $deja_evalue = !$est_mentor
            && $sess['statut'] === 'terminee'
            && in_array($sess['id'], $deja_evalues);

        $peut_annuler = in_array($sess['statut'], ['en_attente','confirmee']);

        $subtitle = addslashes($sess['matiere_nom'] . ' · ' . $jour . ' ' . date('d',$ts) . ' ' . $mois);
    ?>
    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100
                hover:border-gray-200 transition-colors bg-white">

        <!-- Date -->
        <div class="text-center bg-gray-50 rounded-xl p-2.5 min-w-[52px] flex-shrink-0">
            <p class="text-xs text-gray-400 uppercase font-medium leading-none">
                <?= strtoupper(substr($mois,0,3)) ?>
            </p>
            <p class="font-syne font-bold text-gray-900 text-xl leading-tight">
                <?= date('d', $ts) ?>
            </p>
        </div>

        <!-- Infos -->
        <div class="flex-1 min-w-0">
            <p class="font-medium text-gray-900 text-sm truncate">
                <?= e($sess['matiere_nom']) ?>
                <span class="text-gray-400 font-normal text-xs"> · <?= $role_label ?> :</span>
                <?= e($interlocuteur) ?>
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
                <?= $jour ?> <?= date('d',$ts) ?> <?= $mois ?>
                &nbsp;·&nbsp; <?= $debut ?> – <?= $fin ?>
                &nbsp;·&nbsp; <?= $enLigne ? 'En ligne' : 'Présentiel' ?>
            </p>

            <!-- Lien visio -->
            <?php if ($enLigne && !empty($sess['lien_session']) && $sess['statut'] === 'confirmee'): ?>
            <a href="<?= e($sess['lien_session']) ?>" target="_blank" rel="noopener"
               class="inline-flex items-center gap-1 mt-1 text-xs text-teal-600
                      hover:text-teal-700 hover:underline font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.069A1 1 0 0121 8.868v6.264a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                Rejoindre la visio
            </a>
            <?php endif; ?>
        </div>

        <!-- Actions + Statut -->
        <div class="flex items-center gap-2 flex-shrink-0">

            <!-- Bouton Évaluer -->
            <?php if ($peut_evaluer): ?>
            <a href="<?= APP_URL ?>/?url=evaluer&session_id=<?= $sess['id'] ?>"
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50
                      text-amber-700 text-xs font-medium rounded-lg
                      hover:bg-amber-100 transition-colors">
                <span class="text-base leading-none">★</span>
                Évaluer
            </a>
            <?php elseif ($deja_evalue): ?>
            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100
                         text-gray-400 text-xs font-medium rounded-lg">
                <span class="text-base leading-none">★</span>
                Noté
            </span>
            <?php endif; ?>

            <!-- Bouton Annuler -->
            <?php if ($peut_annuler): ?>
            <button onclick="ouvrirAnnuler(<?= $sess['id'] ?>, '<?= $subtitle ?>')"
                    class="px-3 py-1.5 border border-red-200 text-red-500 text-xs
                           font-medium rounded-lg hover:bg-red-50 transition-colors">
                Annuler
            </button>
            <?php endif; ?>

            <!-- Badge statut -->
            <?php
            $badges = [
                'en_attente' => 'bg-amber-50 text-amber-700',
                'confirmee'  => 'bg-teal-50 text-teal-700',
                'terminee'   => 'bg-gray-100 text-gray-500',
                'annulee'    => 'bg-red-50 text-red-500',
            ];
            $labels = [
                'en_attente' => 'En attente',
                'confirmee'  => 'Confirmée',
                'terminee'   => 'Terminée',
                'annulee'    => 'Annulée',
            ];
            $cls = $badges[$sess['statut']] ?? 'bg-gray-100 text-gray-500';
            $lib = $labels[$sess['statut']] ?? ucfirst($sess['statut']);
            ?>
            <span class="px-3 py-1.5 rounded-lg text-xs font-medium <?= $cls ?>">
                <?= $lib ?>
            </span>

        </div>
    </div>
    <?php }; // fin closure afficher_carte ?>

    <!-- ── SESSIONS À VENIR ── -->
    <?php if (!empty($a_venir)): ?>
    <div class="mb-8">
        <h2 class="font-syne text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-teal-400"></span>
            À venir
            <span class="text-xs font-normal text-gray-400">(<?= count($a_venir) ?>)</span>
        </h2>
        <div class="space-y-2">
            <?php foreach ($a_venir as $sess) { $afficher_carte($sess); } ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── SESSIONS TERMINÉES ── -->
    <?php if (!empty($terminees)): ?>
    <div class="mb-8">
        <h2 class="font-syne text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
            Terminées
            <span class="text-xs font-normal text-gray-400">(<?= count($terminees) ?>)</span>
        </h2>
        <div class="space-y-2">
            <?php foreach ($terminees as $sess) { $afficher_carte($sess); } ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── SESSIONS ANNULÉES ── -->
    <?php if (!empty($annulees)): ?>
    <details class="mb-4">
        <summary class="font-syne text-base font-bold text-gray-400 mb-3
                        flex items-center gap-2 cursor-pointer select-none list-none">
            <span class="w-2 h-2 rounded-full bg-red-300"></span>
            Annulées
            <span class="text-xs font-normal">(<?= count($annulees) ?>)</span>
            <svg class="w-4 h-4 ml-auto transition-transform" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </summary>
        <div class="space-y-2 mt-3">
            <?php foreach ($annulees as $sess) { $afficher_carte($sess); } ?>
        </div>
    </details>
    <?php endif; ?>

    <?php endif; // fin empty($sessions) ?>

</main>
</div>

<script>
function ouvrirAnnuler(sessionId, subtitle) {
    document.getElementById('annuler-session-id').value = sessionId;
    document.getElementById('annuler-subtitle').textContent = subtitle;
    document.getElementById('modal-annuler').classList.remove('hidden');
}
function fermerModal() {
    document.getElementById('modal-annuler').classList.add('hidden');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') fermerModal();
});
</script>

</body>
</html>