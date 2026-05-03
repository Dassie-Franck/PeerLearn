<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir mentor — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/etudiant/demande_mentor.css">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="bg-gray-50 min-h-screen flex">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<main class="flex-1 py-8 px-4 max-w-2xl mx-auto w-full">

    <!-- En-tête -->
    <div class="mb-8">
        <a href="<?= APP_URL ?>/?url=profil"
           class="text-gray-500 text-sm inline-flex items-center gap-1.5 mb-4 hover:text-gray-700 transition">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour au profil
        </a>
        <h1 class="font-syne text-2xl font-bold text-gray-900 mb-2">Devenir mentor</h1>
        <p class="text-gray-500 text-sm">
            Partage tes compétences et aide tes camarades.
            Ta demande sera examinée par un administrateur.
        </p>
    </div>

    <!-- Bandeau demande en cours -->
    <?php if (!empty($_SESSION['est_mentor']) && !is_mentor()): ?>
    <div class="alert-warning">
        <svg width="20" height="20" fill="none" stroke="#92400E" viewBox="0 0 24 24" class="flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-yellow-800 m-0">
            Ta demande est en cours d'examen. Tu seras notifié dès que possible.
        </p>
    </div>
    <?php endif; ?>

    <!-- Info -->
    <div class="alert-info">
        <svg width="20" height="20" fill="none" stroke="#1D4ED8" viewBox="0 0 24 24" class="flex-shrink-0 mt-0.5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-blue-800 m-0 leading-relaxed">
            <strong>Comment ça fonctionne ?</strong><br>
            Remplis ce formulaire → L'admin valide ton profil → Tu peux publier tes
            disponibilités et recevoir des demandes de session.
        </p>
    </div>

    <!-- Formulaire -->
    <div class="card">
        <form method="POST" action="<?= APP_URL ?>/?url=demande-mentor">
            <?= csrf_field() ?>

            <!-- Bio -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Présentation / Bio <span class="text-red-500">*</span>
                </label>
                <textarea name="bio" rows="4" required
                    placeholder="Décris-toi : ton parcours, tes points forts, pourquoi tu veux devenir mentor..."
                    class="input-field"></textarea>
            </div>

            <!-- Expérience -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Ton expérience <span class="text-red-500">*</span>
                </label>
                <textarea name="experience" rows="4" required
                    placeholder="Ex : 17/20 en Maths en terminale, j'ai aidé plusieurs camarades..."
                    class="input-field"></textarea>
            </div>

            <!-- Matières -->
            <div class="mb-7">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Matières à enseigner <span class="text-red-500">*</span>
                </label>
                <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                <div class="mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        <?= e($categorie) ?>
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($mats as $mat): ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="matieres[]"
                                   value="<?= $mat['id'] ?>"
                                   class="hidden"
                                   onchange="toggleChip(this)">
                            <span class="chip"><?= e($mat['nom']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    Envoyer ma demande
                </button>
                <a href="<?= APP_URL ?>/?url=profil" class="btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>

</main>

<script>
function toggleChip(cb) {
    cb.nextElementSibling.classList.toggle('chip-active', cb.checked);
}

// Initialisation : si la page est rechargée avec des erreurs, restaurer les chips actives
document.querySelectorAll('input[type="checkbox"][name="matieres[]"]').forEach(cb => {
    if (cb.checked) {
        cb.nextElementSibling.classList.add('chip-active');
    }
});
</script>

</body>
</html>