<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/auth/register.css">
    
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
            Rejoins la<br>communauté.
        </h1>
        <p class="text-gray-400 text-lg leading-relaxed mb-10">
            Crée ton compte et commence à apprendre ou enseigner dès aujourd'hui.
        </p>
        <ul class="space-y-4">
            <?php foreach ([
                'Accès 100% gratuit',
                'Sessions en ligne ou en présentiel',
                'Choisis tes mentors selon ta matière',
                'Deviens mentor et partage tes compétences',
            ] as $item): ?>
            <li class="flex items-center gap-3 text-gray-300 text-sm">
                <span class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs flex-shrink-0"
                      style="background:#5B4FE8">✓</span>
                <?= $item ?>
            </li>
            <?php endforeach; ?>
        </ul>
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

        <h2 class="font-syne text-2xl font-bold text-gray-900 mb-2">Créer un compte</h2>
        <p class="text-gray-500 text-sm mb-8">Remplis le formulaire pour commencer.</p>

        <form method="POST" action="<?= APP_URL ?>/?url=register" novalidate>
            <?= csrf_field() ?>

            <!-- Prénom + Nom -->
            <div class="flex gap-4 mb-5">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom</label>
                    <input type="text" name="prenom"
                           value="<?= e($old['prenom'] ?? '') ?>"
                           placeholder="Jean" class="input-field" required autofocus>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom</label>
                    <input type="text" name="nom"
                           value="<?= e($old['nom'] ?? '') ?>"
                           placeholder="Dupont" class="input-field" required>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <input type="email" name="email"
                       value="<?= e($old['email'] ?? '') ?>"
                       placeholder="jean.dupont@email.com" class="input-field" required>
            </div>

            <!-- Mot de passe -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe</label>
                <div class="relative">
                    <input type="password" id="mdp" name="mot_de_passe"
                           placeholder="8 caractères minimum"
                           class="input-field" style="padding-right:48px"
                           required oninput="checkForce(this.value)">
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
                
                <!-- Barre de force du mot de passe -->
                <div class="force-meter">
                    <div id="force-bar" class="force-meter-bar"></div>
                </div>
                <p id="force-label" class="force-label"></p>
            </div>

            <!-- Confirmation -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                <div class="relative">
                    <input type="password" id="mdp2" name="mot_de_passe_confirm"
                           placeholder="••••••••"
                           class="input-field" style="padding-right:48px" required>
                    <button type="button" onclick="toggleMdp('mdp2')"
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

            <button type="submit" class="btn-primary w-full">Créer mon compte</button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Déjà un compte ?
            <a href="<?= APP_URL ?>/?url=login" class="font-medium" style="color:#5B4FE8">
                Se connecter
            </a>
        </p>

    </div>
</div>

<script>
function toggleMdp(id) {
    const i = document.getElementById(id);
    i.type = i.type === 'password' ? 'text' : 'password';
}

function checkForce(val) {
    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    
    const cfg = [
        { w: '0%',   c: '',         t: '' },
        { w: '25%',  c: '#EF4444',  t: 'Faible' },
        { w: '50%',  c: '#F59E0B',  t: 'Moyen' },
        { w: '75%',  c: '#3B82F6',  t: 'Bon' },
        { w: '100%', c: '#10B981',  t: 'Excellent' }
    ];
    
    document.getElementById('force-bar').style.width = cfg[score].w;
    document.getElementById('force-bar').style.backgroundColor = cfg[score].c;
    document.getElementById('force-label').textContent = cfg[score].t;
    document.getElementById('force-label').style.color = cfg[score].c || '#9CA3AF';
}
</script>
</body>
</html>