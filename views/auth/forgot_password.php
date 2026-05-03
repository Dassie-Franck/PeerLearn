<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublie — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">

<?php require_once APP_ROOT . '/views/layouts/toast.php'; ?>

<div class="w-full max-w-md px-6">

    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="<?= APP_URL ?>/login">
            <span class="font-syne text-2xl font-bold text-gray-900">
                Peer<span class="text-violet">Learn</span>
            </span>
        </a>
    </div>

    <!-- Icone -->
    <div class="w-14 h-14 bg-violet bg-opacity-10 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-7 h-7 text-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>

    <h2 class="font-syne text-2xl font-bold text-gray-900 text-center mb-2">Mot de passe oublie ?</h2>
    <p class="text-gray-500 text-sm text-center mb-8">
        Entre ton adresse email et on t enverra un lien pour reinitialiser ton mot de passe.
    </p>

    <div class="card">
        <form method="POST" action="<?= APP_URL ?>/forgot" novalidate>
            <?= csrf_field() ?>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Adresse email
                </label>
                <input type="email" id="email" name="email"
                       placeholder="ton@email.com"
                       class="input-field" required autofocus>
            </div>

            <button type="submit" class="btn-primary w-full text-center">
                Envoyer le lien
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        <a href="<?= APP_URL ?>/login" class="text-violet hover:underline flex items-center justify-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour a la connexion
        </a>
    </p>

</div>

</body>
</html>