<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil mentor — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/mentor/profil-public.css">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_mentor.php'; ?>

<main class="flex-1 py-8 px-4 max-w-2xl mx-auto w-full">

    <h1 class="font-syne text-2xl font-bold text-gray-900 mb-2">Mon profil mentor</h1>
    <p class="text-gray-500 text-sm mb-8">
        Ces informations sont visibles par les étudiants sur ta fiche publique.
    </p>

    <!-- ONGLETS -->
    <div class="tabs-container">
        <button class="tab-btn" data-tab="bio">Bio & Expérience</button>
        <button class="tab-btn" data-tab="matieres">Matières</button>
        <button class="tab-btn" data-tab="disponibilite">Disponibilité</button>
    </div>

    <!-- ===== BIO & EXPERIENCE ===== -->
    <div id="panel-bio" class="tab-panel">
        <div class="card">
            <form method="POST" action="<?= APP_URL ?>/mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_profil">

                <div class="mb-5">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" rows="4" required
                        class="form-textarea"
                        onfocus="this.style.borderColor='#0FC4A7'"
                        onblur="this.style.borderColor='#E5E7EB'"><?= e($profil_mentor['bio'] ?? '') ?></textarea>
                </div>

                <div class="mb-6">
                    <label class="form-label">Expérience</label>
                    <textarea name="experience" rows="4" required
                        class="form-textarea"
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
    <div id="panel-matieres" class="tab-panel" style="display: none;">
        <div class="card">
            <p class="text-sm text-gray-500 mb-5">
                Sélectionne les matières que tu enseignes.
            </p>
            <form method="POST" action="<?= APP_URL ?>/mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_matieres">

                <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                <div class="mb-5">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2.5">
                        <?= e($categorie) ?>
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($mats as $mat): ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="matieres[]"
                                   value="<?= $mat['id'] ?>"
                                   <?= in_array($mat['id'], $ids_mentor) ? 'checked' : '' ?>
                                   class="hidden"
                                   onchange="toggleChip(this)">
                            <span class="chip <?= in_array($mat['id'], $ids_mentor) ? 'chip-active' : '' ?>">
                                <?= e($mat['nom']) ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <button type="submit" class="btn-primary mt-2">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

    <!-- ===== DISPONIBILITE ===== -->
    <div id="panel-disponibilite" class="tab-panel" style="display: none;">
        <div class="card">
            <p class="text-sm text-gray-500 mb-5">
                Indique ton statut de disponibilité général.
            </p>
            <form method="POST" action="<?= APP_URL ?>/mentor-profil">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_statut">

                <?php
                $statuts = [
                    'disponible' => ['label' => 'Disponible', 'dot' => 'disponible', 'bg' => '#F0FDF4', 'border' => '#BBF7D0', 'color' => '#166534'],
                    'occupe'     => ['label' => 'Occupé',     'dot' => 'occupe',     'bg' => '#FFFBEB', 'border' => '#FDE68A', 'color' => '#92400E'],
                    'inactif'    => ['label' => 'Inactif',    'dot' => 'inactif',    'bg' => '#FFF1F2', 'border' => '#FECDD3', 'color' => '#991B1B'],
                ];
                foreach ($statuts as $val => $s):
                    $actif = ($profil_mentor['statut_dispo'] ?? 'disponible') === $val;
                ?>
                <label class="statut-option <?= $actif ? 'active' : '' ?>" data-statut="<?= $val ?>">
                    <input type="radio" name="statut_dispo" value="<?= $val ?>"
                           <?= $actif ? 'checked' : '' ?>
                           class="w-4 h-4 accent-teal-500 hidden">
                    <span class="statut-dot <?= $s['dot'] ?>"></span>
                    <span class="statut-label <?= $actif ? 'text-green-800' : 'text-gray-700' ?>">
                        <?= $s['label'] ?>
                    </span>
                </label>
                <?php endforeach; ?>

                <button type="submit" class="btn-primary mt-2">
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

</main>

<script>
// ==================== GESTION DES ONGLETS ====================
function showTab(tabName) {
    // Cacher tous les panneaux
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.style.display = 'none';
    });
    
    // Afficher le panneau sélectionné
    const panel = document.getElementById('panel-' + tabName);
    if (panel) panel.style.display = 'block';
    
    // Mettre à jour les classes des boutons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-tab') === tabName) {
            btn.classList.add('active');
        }
    });
}

// ==================== CHIP TOGGLE ====================
function toggleChip(checkbox) {
    const chip = checkbox.nextElementSibling;
    if (checkbox.checked) {
        chip.classList.add('chip-active');
    } else {
        chip.classList.remove('chip-active');
    }
}

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les data-tab aux boutons
    const tabs = ['bio', 'matieres', 'disponibilite'];
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach((btn, index) => {
        btn.setAttribute('data-tab', tabs[index]);
        btn.addEventListener('click', () => showTab(tabs[index]));
    });
    
    // Afficher l'onglet bio par défaut
    showTab('bio');
    
    // Ajouter l'effet de sélection sur les options de statut
    document.querySelectorAll('.statut-option').forEach(opt => {
        const radio = opt.querySelector('input[type="radio"]');
        if (radio) {
            opt.addEventListener('click', () => {
                radio.checked = true;
                document.querySelectorAll('.statut-option').forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                const label = opt.querySelector('.statut-label');
                if (label) {
                    const colors = { 'disponible': 'text-green-800', 'occupe': 'text-amber-800', 'inactif': 'text-red-800' };
                    const val = radio.value;
                    document.querySelectorAll('.statut-label').forEach(l => {
                        l.classList.remove('text-green-800', 'text-amber-800', 'text-red-800');
                        l.classList.add('text-gray-700');
                    });
                    label.classList.remove('text-gray-700');
                    label.classList.add(colors[val] || 'text-gray-700');
                }
            });
        }
    });
});
</script>

</body>
</html>
