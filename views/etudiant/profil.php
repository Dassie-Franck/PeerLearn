<?php
// ============================================================
//  views/etudiant/profil.php
//  Profil étudiant - Design moderne
// ============================================================

$page_active = 'profil';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/etudiant/profil.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main class="flex-1 p-8 max-w-[900px] w-full mx-auto">
    
    <!-- Header -->
    <div class="animate-fadeInUp mb-7">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
            <i class="fa-regular fa-user" style="color: #5B4FE8; margin-right: 12px;"></i>
            Mon profil
        </h1>
        <p class="text-gray-500 text-sm">
            Gérez vos informations personnelles et vos préférences
        </p>
    </div>
    
    <!-- ==================== TABS ==================== -->
    <div class="tabs-container">
        <button class="tab-btn active" onclick="showTab('infos')">
            <i class="fa-regular fa-circle-user" style="margin-right: 8px;"></i>
            Informations
        </button>
        <button class="tab-btn" onclick="showTab('matieres')">
            <i class="fa-solid fa-book" style="margin-right: 8px;"></i>
            Mes matières
        </button>
        <button class="tab-btn" onclick="showTab('securite')">
            <i class="fa-solid fa-lock" style="margin-right: 8px;"></i>
            Sécurité
        </button>
    </div>
    
    <!-- ==================== TAB 1 : INFORMATIONS ==================== -->
    <div id="tab-infos" class="animate-slideInRight">
        <div class="profile-card">
            <form method="POST" action="<?= APP_URL ?>/?url=profil" enctype="multipart/form-data" class="p-7">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_infos">
                
                <!-- Photo de profil -->
                <div class="flex items-center gap-6 mb-7">
                    <?php if (!empty($utilisateur['photo'])): ?>
                        <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" 
                             class="avatar-large" alt="Photo de profil">
                    <?php else: ?>
                        <div class="avatar-initial">
                            <?= strtoupper(substr($utilisateur['prenom'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-1.5">
                            Photo de profil
                        </p>
                        <input type="file" name="photo" accept="image/*"
                               class="text-sm text-gray-500 mb-1.5">
                        <p class="text-xs text-gray-400">
                            <i class="fa-regular fa-image"></i> JPG, PNG, WEBP — max 2 Mo
                        </p>
                    </div>
                </div>
                
                <!-- Prénom et Nom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-regular fa-user" style="margin-right: 6px;"></i> Prénom
                        </label>
                        <input type="text" name="prenom" value="<?= e($utilisateur['prenom'] ?? '') ?>" 
                               class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-regular fa-user" style="margin-right: 6px;"></i> Nom
                        </label>
                        <input type="text" name="nom" value="<?= e($utilisateur['nom'] ?? '') ?>" 
                               class="form-input" required>
                    </div>
                </div>
                
                <!-- Email (non modifiable) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-regular fa-envelope" style="margin-right: 6px;"></i> Adresse email
                    </label>
                    <input type="email" value="<?= e($utilisateur['email'] ?? '') ?>" 
                           class="form-input" disabled>
                    <p class="text-xs text-gray-400 mt-1.5">
                        <i class="fa-regular fa-info-circle"></i> L'adresse email ne peut pas être modifiée
                    </p>
                </div>
                
                <div class="divider"></div>
                
                <div class="flex justify-end">
                    <button type="submit" class="btn-save">
                        <i class="fa-regular fa-floppy-disk"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
            
            <!-- ===== BLOC DEVENIR MENTOR ===== -->
            <div class="px-7 pb-7">
                <div class="divider"></div>
                
                <?php if (empty($utilisateur['est_mentor'])): ?>
                <!-- Pas encore mentor -->
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-1">
                            <i class="fa-solid fa-graduation-cap" style="color: #F59E0B; margin-right: 8px;"></i>
                            Tu veux partager tes compétences ?
                        </p>
                        <p class="text-sm text-gray-500">
                            Deviens mentor et aide tes camarades à réussir
                        </p>
                    </div>
                    <a href="<?= APP_URL ?>/?url=demande-mentor" class="btn-mentor">
                        Devenir mentor <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <?php elseif (!is_mentor()): ?>
                <!-- Demande en attente -->
                <div class="status-warning">
                    <i class="fa-regular fa-clock" style="color: #D97706; font-size: 20px;"></i>
                    <p class="text-sm text-amber-800 m-0 flex-1">
                        ⏳ Votre demande de mentorat est en cours d'examen par l'administrateur.
                    </p>
                </div>
                
                <?php else: ?>
                <!-- Mentor validé -->
                <div class="status-success">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle" style="color: #166534; font-size: 20px;"></i>
                        <p class="text-sm text-green-800 m-0 font-semibold">
                            ✓ Vous êtes mentor validé sur PeerLearn
                        </p>
                    </div>
                    <a href="<?= APP_URL ?>/?url=mentor" class="text-sm text-green-800 no-underline font-semibold">
                        Espace mentor <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- ==================== TAB 2 : MATIÈRES ==================== -->
    <div id="tab-matieres" style="display: none;" class="animate-slideInRight">
        <div class="profile-card">
            <form method="POST" action="<?= APP_URL ?>/?url=profil" class="p-7">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_matieres">
                
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-5">
                        <i class="fa-regular fa-circle-info"></i> Sélectionne les matières dans lesquelles tu souhaites recevoir de l'aide
                    </p>
                    
                    <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                    <div class="mb-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">
                            <?= e($categorie) ?>
                        </p>
                        <div class="flex flex-wrap gap-2.5">
                            <?php foreach ($mats as $mat): ?>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="matieres[]" value="<?= $mat['id'] ?>"
                                       <?= in_array($mat['id'], $ids_etudiant) ? 'checked' : '' ?>
                                       class="hidden"
                                       onchange="toggleChip(this)">
                                <span class="chip <?= in_array($mat['id'], $ids_etudiant) ? 'chip-active' : '' ?>">
                                    <?= e($mat['nom']) ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="divider"></div>
                
                <div class="flex justify-end">
                    <button type="submit" class="btn-save">
                        <i class="fa-regular fa-floppy-disk"></i> Enregistrer mes matières
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- ==================== TAB 3 : SÉCURITÉ ==================== -->
    <div id="tab-securite" style="display: none;" class="animate-slideInRight">
        <div class="profile-card">
            <form method="POST" action="<?= APP_URL ?>/?url=profil" class="p-7">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_mdp">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-key" style="margin-right: 6px;"></i> Ancien mot de passe
                    </label>
                    <input type="password" name="ancien_mdp" class="form-input" required>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-lock" style="margin-right: 6px;"></i> Nouveau mot de passe
                        </label>
                        <input type="password" name="nouveau_mdp" class="form-input" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fa-solid fa-check-circle" style="margin-right: 6px;"></i> Confirmer le mot de passe
                        </label>
                        <input type="password" name="confirm_mdp" class="form-input" required minlength="8">
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="flex justify-end">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-shield-haltered"></i> Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</main>

<script>
// ==================== TAB MANAGEMENT ====================
function showTab(tabName) {
    // Cacher tous les panels
    document.getElementById('tab-infos').style.display = 'none';
    document.getElementById('tab-matieres').style.display = 'none';
    document.getElementById('tab-securite').style.display = 'none';
    
    // Afficher le panel sélectionné
    document.getElementById(`tab-${tabName}`).style.display = 'block';
    
    // Mettre à jour les classes des boutons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Trouver et activer le bon bouton
    const buttons = document.querySelectorAll('.tab-btn');
    const tabMap = { 'infos': 0, 'matieres': 1, 'securite': 2 };
    if (buttons[tabMap[tabName]]) {
        buttons[tabMap[tabName]].classList.add('active');
    }
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
    showTab('infos');
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>