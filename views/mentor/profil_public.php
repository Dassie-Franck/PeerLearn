<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil mentor — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body style="background:#F9FAFB;display:flex;min-height:100vh">

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<main style="flex:1;padding:32px;max-width:800px;width:100%">

    <h1 class="font-syne" style="font-size:24px;font-weight:700;color:#111827;margin:0 0 8px">
        Mon profil mentor
    </h1>
    <p style="color:#6B7280;font-size:14px;margin:0 0 32px">
        Ces informations sont visibles par les etudiants sur ta fiche publique.
    </p>

    <!-- ONGLETS -->
    <div style="display:flex;gap:0;border-bottom:2px solid #F3F4F6;margin-bottom:32px">
        <?php foreach (['bio' => 'Bio & Experience', 'matieres' => 'Matieres', 'disponibilite' => 'Disponibilite'] as $k => $l): ?>
        <button onclick="showTab('<?= $k ?>')" id="tab-<?= $k ?>"
                style="padding:10px 20px;background:none;border:none;cursor:pointer;
                       font-size:14px;font-family:'DM Sans',sans-serif;color:#6B7280;
                       border-bottom:2px solid transparent;margin-bottom:-2px">
            <?= $l ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- ===== BIO & EXPERIENCE ===== -->
    <div id="panel-bio">
        <div class="card">
            <form method="POST" action="<?= APP_URL ?>/?url=mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_profil">

                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:13px;font-weight:500;
                                  color:#374151;margin-bottom:6px">Bio</label>
                    <textarea name="bio" rows="4" required
                        style="width:100%;padding:12px 16px;border:1px solid #E5E7EB;
                               border-radius:12px;font-size:14px;font-family:'DM Sans',sans-serif;
                               color:#111827;resize:vertical;outline:none;
                               box-sizing:border-box;transition:border-color .2s"
                        onfocus="this.style.borderColor='#0FC4A7'"
                        onblur="this.style.borderColor='#E5E7EB'"><?= e($profil_mentor['bio'] ?? '') ?></textarea>
                </div>

                <div style="margin-bottom:24px">
                    <label style="display:block;font-size:13px;font-weight:500;
                                  color:#374151;margin-bottom:6px">Experience</label>
                    <textarea name="experience" rows="4" required
                        style="width:100%;padding:12px 16px;border:1px solid #E5E7EB;
                               border-radius:12px;font-size:14px;font-family:'DM Sans',sans-serif;
                               color:#111827;resize:vertical;outline:none;
                               box-sizing:border-box;transition:border-color .2s"
                        onfocus="this.style.borderColor='#0FC4A7'"
                        onblur="this.style.borderColor='#E5E7EB'"><?= e($profil_mentor['experience'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-primary">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

    <!-- ===== MATIERES ===== -->
    <div id="panel-matieres" style="display:none">
        <div class="card">
            <p style="font-size:14px;color:#6B7280;margin:0 0 20px">
                Selectionne les matieres que tu enseignes.
            </p>
            <form method="POST" action="<?= APP_URL ?>/?url=mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_matieres">

                <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                <div style="margin-bottom:20px">
                    <p style="font-size:11px;font-weight:600;color:#9CA3AF;
                               text-transform:uppercase;letter-spacing:.08em;margin:0 0 10px">
                        <?= e($categorie) ?>
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px">
                        <?php foreach ($mats as $mat): ?>
                        <label style="cursor:pointer">
                            <input type="checkbox" name="matieres[]"
                                   value="<?= $mat['id'] ?>"
                                   <?= in_array($mat['id'], $ids_mentor) ? 'checked' : '' ?>
                                   style="display:none"
                                   onchange="toggleChip(this)">
                            <span class="chip <?= in_array($mat['id'], $ids_mentor) ? 'chip-active' : '' ?>">
                                <?= e($mat['nom']) ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <button type="submit" class="btn-primary" style="margin-top:8px">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

    <!-- ===== DISPONIBILITE ===== -->
    <div id="panel-disponibilite" style="display:none">
        <div class="card">
            <p style="font-size:14px;color:#6B7280;margin:0 0 20px">
                Indique ton statut de disponibilite general.
            </p>
            <form method="POST" action="<?= APP_URL ?>/?url=mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_statut">

                <?php
                $statuts = [
                    'disponible' => ['label' => 'Disponible',  'color' => '#166534', 'bg' => '#F0FDF4', 'border' => '#BBF7D0', 'dot' => '#22C55E'],
                    'occupe'     => ['label' => 'Occupe',       'color' => '#92400E', 'bg' => '#FFFBEB', 'border' => '#FDE68A', 'dot' => '#F59E0B'],
                    'inactif'    => ['label' => 'Inactif',      'color' => '#991B1B', 'bg' => '#FFF1F2', 'border' => '#FECDD3', 'dot' => '#EF4444'],
                ];
                foreach ($statuts as $val => $s):
                    $actif = ($profil_mentor['statut_dispo'] ?? 'disponible') === $val;
                ?>
                <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;
                              border-radius:12px;border:2px solid <?= $actif ? $s['border'] : '#E5E7EB' ?>;
                              background:<?= $actif ? $s['bg'] : '#fff' ?>;
                              cursor:pointer;margin-bottom:10px;transition:all .15s">
                    <input type="radio" name="statut_dispo" value="<?= $val ?>"
                           <?= $actif ? 'checked' : '' ?>
                           style="width:16px;height:16px;accent-color:<?= $s['dot'] ?>">
                    <span style="width:10px;height:10px;border-radius:50%;
                                 background:<?= $s['dot'] ?>;flex-shrink:0"></span>
                    <span style="font-size:14px;font-weight:500;color:<?= $actif ? $s['color'] : '#374151' ?>">
                        <?= $s['label'] ?>
                    </span>
                </label>
                <?php endforeach; ?>

                <button type="submit" class="btn-primary" style="margin-top:8px">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

</main>
</div>

<style>
.chip        { display:inline-block;padding:6px 14px;border-radius:20px;font-size:13px;border:1px solid #E5E7EB;color:#6B7280;transition:all .15s;user-select:none; }
.chip:hover  { border-color:#0FC4A7;color:#0FC4A7; }
.chip-active { background:#0FC4A7;color:#fff;border-color:#0FC4A7; }
.tab-active  { color:#0FC4A7 !important;border-bottom-color:#0FC4A7 !important; }
</style>

<script>
function showTab(name) {
    ['bio','matieres','disponibilite'].forEach(p => {
        document.getElementById('panel-' + p).style.display = p === name ? 'block' : 'none';
        document.getElementById('tab-' + p).classList.toggle('tab-active', p === name);
    });
}
function toggleChip(cb) {
    cb.nextElementSibling.classList.toggle('chip-active', cb.checked);
}
showTab('bio');
</script>

</body>
</html>