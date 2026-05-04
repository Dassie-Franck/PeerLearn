<?php
// ============================================================
//  views/auth/forgot_reset.php
//  Vue uniquement — aucune logique métier ici
//  Variables attendues depuis le controller :
//    $errors  array   Liste des erreurs
//    $token   string  Token de réinitialisation (pour l'action du form)
// ============================================================

$pageTitle = 'Nouveau mot de passe — PeerLearn';
require_once __DIR__ . '/_layout_head.php';
?>

<div class="auth-card">

    <div class="logo">PeerLearn</div>
    <h1 class="auth-title">Nouveau mot de passe</h1>
    <p class="auth-subtitle">Choisissez un mot de passe sécurisé</p>

    <!-- Erreurs de validation -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form
        method="POST"
        action="/controllers/auth/forgot_controller.php?token=<?= urlencode($token) ?>"
        novalidate
    >

        <!-- Nouveau mot de passe -->
        <div class="field">
            <label for="password">Nouveau mot de passe</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min. 8 car., 1 majuscule, 1 chiffre"
                    autocomplete="new-password"
                    oninput="updateStrength(this.value)"
                    required
                >
                <button type="button" class="btn-icon" onclick="togglePassword('password')" title="Afficher / Masquer">👁</button>
            </div>
            <div class="pw-meter">
                <div class="pw-bar">
                    <div class="pw-fill" id="pw-fill"></div>
                </div>
                <span class="pw-label" id="pw-label">Entrez un mot de passe</span>
            </div>
        </div>

        <!-- Confirmation -->
        <div class="field">
            <label for="confirm">Confirmer le mot de passe</label>
            <div class="input-wrap">
                <input
                    type="password"
                    id="confirm"
                    name="confirm"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
                <button type="button" class="btn-icon" onclick="togglePassword('confirm')" title="Afficher / Masquer">👁</button>
            </div>
        </div>

        <button type="submit" class="btn-primary">Changer le mot de passe</button>

    </form>

</div>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function updateStrength(pw) {
        const fill  = document.getElementById('pw-fill');
        const label = document.getElementById('pw-label');

        const levels = [
            { test: pw.length === 0,                          score: 0, color: '',         text: 'Entrez un mot de passe' },
            { test: pw.length < 6,                            score: 1, color: '#ff6b6b',  text: 'Trop court' },
            { test: pw.length < 8,                            score: 2, color: '#f0a500',  text: 'Faible' },
            { test: !/[A-Z]/.test(pw) || !/[0-9]/.test(pw),  score: 3, color: '#e8c547',  text: 'Moyen' },
            { test: true,                                     score: 4, color: '#6bcb77',  text: 'Fort' },
        ];

        const level = levels.find(l => l.test);

        fill.style.width      = (level.score * 25) + '%';
        fill.style.background = level.color;
        label.textContent     = level.text;
        label.style.color     = level.color || 'var(--muted)';
    }
</script>

<?php require_once __DIR__ . '/_layout_foot.php'; ?>
