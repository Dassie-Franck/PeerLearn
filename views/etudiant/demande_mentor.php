<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir mentor — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body style="background:#F9FAFB;display:flex;min-height:100vh">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<main style="flex:1;padding:32px;max-width:800px;width:100%">

    <!-- En-tete -->
    <div style="margin-bottom:32px">
        <a href="<?= APP_URL ?>/?url=profil"
           style="font-size:13px;color:#6B7280;text-decoration:none;
                  display:inline-flex;align-items:center;gap:6px;margin-bottom:16px">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour au profil
        </a>
        <h1 class="font-syne" style="font-size:24px;font-weight:700;color:#111827;margin:0 0 8px">
            Devenir mentor
        </h1>
        <p style="color:#6B7280;font-size:14px;margin:0">
            Partage tes competences et aide tes camarades.
            Ta demande sera examinee par un administrateur.
        </p>
    </div>

    <!-- Bandeau demande en cours -->
    <?php if (!empty($_SESSION['est_mentor']) && !is_mentor()): ?>
    <div style="background:#FEF9C3;border:1px solid #FDE68A;border-radius:12px;
                padding:16px 20px;margin-bottom:24px;
                display:flex;align-items:center;gap:12px">
        <svg width="20" height="20" fill="none" stroke="#92400E" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size:14px;color:#92400E;margin:0">
            Ta demande est en cours d examen. Tu seras notifie des que possible.
        </p>
    </div>
    <?php endif; ?>

    <!-- Info -->
    <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;
                padding:16px 20px;margin-bottom:24px;
                display:flex;align-items:flex-start;gap:12px">
        <svg width="20" height="20" fill="none" stroke="#1D4ED8" viewBox="0 0 24 24"
             style="flex-shrink:0;margin-top:2px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size:13px;color:#1E40AF;margin:0;line-height:1.6">
            <strong>Comment ca fonctionne ?</strong><br>
            Remplis ce formulaire → L admin valide ton profil → Tu peux publier tes
            disponibilites et recevoir des demandes de session.
        </p>
    </div>

    <!-- Formulaire -->
    <div class="card">
        <form method="POST" action="<?= APP_URL ?>/?url=demande-mentor">
            <?= csrf_field() ?>

            <!-- Bio -->
            <div style="margin-bottom:20px">
                <label style="display:block;font-size:13px;font-weight:500;
                              color:#374151;margin-bottom:6px">
                    Presentation / Bio <span style="color:#EF4444">*</span>
                </label>
                <textarea name="bio" rows="4" required
                    placeholder="Decris-toi : ton parcours, tes points forts, pourquoi tu veux devenir mentor..."
                    style="width:100%;padding:12px 16px;border:1px solid #E5E7EB;
                           border-radius:12px;font-size:14px;font-family:'DM Sans',sans-serif;
                           color:#111827;resize:vertical;outline:none;
                           box-sizing:border-box;transition:border-color .2s"
                    onfocus="this.style.borderColor='#5B4FE8'"
                    onblur="this.style.borderColor='#E5E7EB'"></textarea>
            </div>

            <!-- Experience -->
            <div style="margin-bottom:24px">
                <label style="display:block;font-size:13px;font-weight:500;
                              color:#374151;margin-bottom:6px">
                    Ton experience <span style="color:#EF4444">*</span>
                </label>
                <textarea name="experience" rows="4" required
                    placeholder="Ex : 17/20 en Maths en terminale, j ai aide plusieurs camarades..."
                    style="width:100%;padding:12px 16px;border:1px solid #E5E7EB;
                           border-radius:12px;font-size:14px;font-family:'DM Sans',sans-serif;
                           color:#111827;resize:vertical;outline:none;
                           box-sizing:border-box;transition:border-color .2s"
                    onfocus="this.style.borderColor='#5B4FE8'"
                    onblur="this.style.borderColor='#E5E7EB'"></textarea>
            </div>

            <!-- Matieres -->
            <div style="margin-bottom:28px">
                <label style="display:block;font-size:13px;font-weight:500;
                              color:#374151;margin-bottom:12px">
                    Matieres a enseigner <span style="color:#EF4444">*</span>
                </label>
                <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                <div style="margin-bottom:16px">
                    <p style="font-size:11px;font-weight:600;color:#9CA3AF;
                               text-transform:uppercase;letter-spacing:.08em;margin:0 0 8px">
                        <?= e($categorie) ?>
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px">
                        <?php foreach ($mats as $mat): ?>
                        <label style="cursor:pointer">
                            <input type="checkbox" name="matieres[]"
                                   value="<?= $mat['id'] ?>"
                                   style="display:none"
                                   onchange="toggleChip(this)">
                            <span class="chip"><?= e($mat['nom']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Boutons -->
            <div style="display:flex;gap:12px">
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
</div>

<style>
.chip { display:inline-block;padding:6px 14px;border-radius:20px;font-size:13px;border:1px solid #E5E7EB;color:#6B7280;transition:all .15s;user-select:none; }
.chip:hover  { border-color:#5B4FE8;color:#5B4FE8; }
.chip-active { background:#5B4FE8;color:#fff;border-color:#5B4FE8; }
</style>

<script>
function toggleChip(cb) {
    cb.nextElementSibling.classList.toggle('chip-active', cb.checked);
}
</script>

</body>
</html>