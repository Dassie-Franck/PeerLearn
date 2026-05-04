<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluer la session — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<main class="flex-1 p-8">

    <!-- Fil d'ariane -->
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="<?= APP_URL ?>/?url=mes-sessions"
           class="hover:text-violet transition-colors">← Mes sessions</a>
    </div>

    <div class="max-w-lg mx-auto">

        <div class="card p-8">

            <!-- En-tête -->
            <div class="text-center mb-8">
                <!-- Avatar mentor -->
                <?php if (!empty($session['mentor_photo'])): ?>
                    <img src="<?= APP_URL ?>/uploads/avatars/<?= e($session['mentor_photo']) ?>"
                         class="w-20 h-20 rounded-2xl object-cover mx-auto mb-4" alt="">
                <?php else: ?>
                    <div class="w-20 h-20 rounded-2xl bg-violet flex items-center justify-center
                                text-white font-bold text-2xl mx-auto mb-4">
                        <?= strtoupper(substr($session['mentor_nom'], 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <h1 class="font-syne text-xl font-bold text-gray-900">
                    Évaluer votre session
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    <?= e($session['matiere_nom']) ?> avec
                    <span class="font-medium text-gray-700"><?= e($session['mentor_nom']) ?></span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    <?php
                    $ts = strtotime($session['date_session']);
                    $jours = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi',
                              'Thursday'=>'Jeudi','Friday'=>'Vendredi',
                              'Saturday'=>'Samedi','Sunday'=>'Dimanche'];
                    $mois  = ['January'=>'janvier','February'=>'février','March'=>'mars',
                              'April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet',
                              'August'=>'août','September'=>'septembre','October'=>'octobre',
                              'November'=>'novembre','December'=>'décembre'];
                    echo ($jours[date('l',$ts)] ?? date('l',$ts)) . ' '
                       . date('d', $ts) . ' '
                       . ($mois[date('F',$ts)] ?? date('F',$ts)) . ' '
                       . date('Y', $ts);
                    ?>
                </p>
            </div>

            <!-- Formulaire -->
            <form method="POST"
                  action="<?= APP_URL ?>/?url=evaluer&session_id=<?= $session['id'] ?>">
                <?= csrfField() ?>

                <!-- ── Étoiles ── -->
                <div class="mb-8">
                    <p class="text-sm font-medium text-gray-700 text-center mb-4">
                        Quelle note donnez-vous à cette session ?
                    </p>

                    <div class="flex justify-center gap-2" id="stars-container">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button"
                                data-val="<?= $i ?>"
                                onclick="setNote(<?= $i ?>)"
                                class="star-btn text-5xl transition-all duration-150
                                       text-gray-200 hover:text-amber-400
                                       hover:scale-110 focus:outline-none">
                            ★
                        </button>
                        <?php endfor; ?>
                    </div>

                    <input type="hidden" name="note" id="note-input" value="0">

                    <p class="text-center text-sm mt-3 h-5 text-amber-600 font-medium"
                       id="note-label"></p>
                </div>

                <!-- ── Commentaire ── -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire
                        <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <textarea name="commentaire"
                              rows="4"
                              maxlength="1000"
                              placeholder="Décrivez votre expérience : pédagogie, clarté des explications, ponctualité..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm
                                     focus:outline-none focus:border-violet-400
                                     focus:ring-2 focus:ring-violet-100
                                     transition-colors resize-none"
                              oninput="updateCounter(this)"></textarea>
                    <p class="text-xs text-gray-400 text-right mt-1">
                        <span id="char-count">0</span>/1000
                    </p>
                </div>

                <!-- ── Boutons ── -->
                <div class="flex gap-3">
                    <a href="<?= APP_URL ?>/?url=mes-sessions"
                       class="flex-1 px-5 py-3 rounded-xl border border-gray-200
                              text-sm text-gray-600 font-medium text-center
                              hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            id="btn-submit"
                            disabled
                            class="flex-1 px-5 py-3 rounded-xl bg-violet text-white
                                   text-sm font-medium transition-colors
                                   disabled:opacity-40 disabled:cursor-not-allowed
                                   hover:enabled:bg-violet-700
                                   flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7"/>
                        </svg>
                        Envoyer l'évaluation
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>
</div>

<script>
const labels = {
    1: 'Très décevant',
    2: 'Décevant',
    3: 'Correct',
    4: 'Bien',
    5: 'Excellent !'
};

let noteActuelle = 0;

function setNote(val) {
    noteActuelle = val;
    document.getElementById('note-input').value = val;
    document.getElementById('note-label').textContent = labels[val] ?? '';
    document.getElementById('btn-submit').disabled = false;

    document.querySelectorAll('.star-btn').forEach(btn => {
        const v = parseInt(btn.dataset.val);
        if (v <= val) {
            btn.classList.remove('text-gray-200');
            btn.classList.add('text-amber-400', 'scale-110');
        } else {
            btn.classList.add('text-gray-200');
            btn.classList.remove('text-amber-400', 'scale-110');
        }
    });
}

// Survol des étoiles
document.querySelectorAll('.star-btn').forEach(btn => {
    btn.addEventListener('mouseenter', () => {
        const v = parseInt(btn.dataset.val);
        document.querySelectorAll('.star-btn').forEach(b => {
            const bv = parseInt(b.dataset.val);
            b.classList.toggle('text-amber-300', bv <= v);
            b.classList.toggle('text-gray-200',  bv >  v && bv > noteActuelle);
        });
    });
    btn.addEventListener('mouseleave', () => {
        document.querySelectorAll('.star-btn').forEach(b => {
            const bv = parseInt(b.dataset.val);
            b.classList.toggle('text-amber-400', bv <= noteActuelle);
            b.classList.toggle('text-gray-200',  bv >  noteActuelle);
            b.classList.toggle('scale-110',      bv <= noteActuelle);
        });
    });
});

function updateCounter(el) {
    document.getElementById('char-count').textContent = el.value.length;
}
</script>

</body>
</html>