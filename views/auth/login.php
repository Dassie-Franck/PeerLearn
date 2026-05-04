<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/auth/login.css">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body class="auth-container">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>

<!-- Panneau gauche -->
<div class="auth-left">
    <a href="<?= APP_URL ?>/?url=login">
        <span class="font-syne text-2xl font-bold text-white">Peer<span style="color:#5B4FE8">Learn</span></span>
    </a>
    <div>
        <h1 class="font-syne text-4xl font-bold text-white leading-tight mb-6">
            Apprends mieux,<br>ensemble.
        </h1>
        <p class="text-gray-400 text-lg leading-relaxed">
            Connecte-toi avec des étudiants-mentors prêts à t'accompagner dans tes matières.
        </p>
        <div class="flex gap-10 mt-10">
            <div>
                <p class="font-syne text-3xl font-bold text-white">200+</p>
                <p class="text-gray-500 text-sm mt-1">Mentors actifs</p>
            </div>
            <div>
                <p class="font-syne text-3xl font-bold text-white">1 200+</p>
                <p class="text-gray-500 text-sm mt-1">Sessions réalisées</p>
            </div>
            <div>
                <p class="font-syne text-3xl font-bold text-white">4.8/5</p>
                <p class="text-gray-500 text-sm mt-1">Note moyenne</p>
            </div>
        </div>
    </div>
    <p class="text-gray-600 text-sm">© 2026 PeerLearn — IUC</p>
</div>

<!-- Panneau droit : formulaire -->
<div class="auth-right">
    <div class="auth-card">

        <div class="lg:hidden text-center mb-8">
            <span class="font-syne text-2xl font-bold" style="color:#0D0D14">
                Peer<span style="color:#5B4FE8">Learn</span>
            </span>
        </div>

        <h2 class="font-syne text-2xl font-bold text-gray-900 mb-2">Bon retour !</h2>
        <p class="text-gray-500 text-sm mb-8">Connecte-toi pour accéder à ta plateforme.</p>

        <form method="POST" action="<?= APP_URL ?>/?url=login" novalidate>
            <?= csrf_field() ?>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <input type="email" name="email"
                       value="<?= e($old['email'] ?? '') ?>"
                       placeholder="ton@email.com"
                       class="input-field" required autofocus>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                <div class="relative">
                    <input type="password" id="mdp" name="mot_de_passe"
                           placeholder="••••••••"
                           class="input-field" style="padding-right:48px" required>
                    <button type="button" onclick="toggleMdp('mdp')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="text-right mb-6">
                <a href="<?= APP_URL ?>/?url=forgot" class="text-sm" style="color:#5B4FE8">
                    Mot de passe oublié ?
                </a>
            </div>

            <button type="submit" class="btn-primary w-full">Se connecter</button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ?
            <a href="<?= APP_URL ?>/?url=register" class="font-medium" style="color:#5B4FE8">
                Créer un compte
            </a>
        </p>

    </div>
</div>

<script>
function toggleMdp(id) {
    const i = document.getElementById(id);
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>