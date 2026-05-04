<?php
// ============================================================
//  views/auth/forgot_expired.php
//  Vue uniquement — aucune logique métier ici
//  Affichée quand le token est invalide ou expiré
// ============================================================

$pageTitle = 'Lien expiré — PeerLearn';
require_once __DIR__ . '/_layout_head.php';
?>

<div class="auth-card text-center">

    <div class="logo">PeerLearn</div>

    <span class="big-icon">⏰</span>

    <h1 class="auth-title text-center">Lien expiré</h1>
    <p class="auth-subtitle text-center">
        Ce lien de réinitialisation est invalide ou a expiré.<br>
        Les liens sont valables <strong style="color: var(--text);">1 heure</strong> après la demande.
    </p>

    <a href="/controllers/auth/forgot_controller.php" class="btn-primary" style="display:block; text-decoration:none; text-align:center;">
        Faire une nouvelle demande
    </a>

    <p class="auth-footer">
        <a href="/controllers/auth/login_controller.php">← Retour à la connexion</a>
    </p>

</div>

<?php require_once __DIR__ . '/_layout_foot.php'; ?>
