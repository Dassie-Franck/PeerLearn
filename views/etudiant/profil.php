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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-slideInRight { animation: slideInRight 0.4s ease-out forwards; }
        
        /* ==================== FORM STYLES ==================== */
        .profile-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .profile-card:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            font-size: 14px;
            transition: all 0.2s;
            background: #fff;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #5B4FE8;
            box-shadow: 0 0 0 3px rgba(91,79,232,0.1);
        }
        
        .form-input:disabled {
            background: #F8FAFC;
            color: #94A3B8;
            cursor: not-allowed;
        }
        
        /* ==================== TABS ==================== */
        .tabs-container {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #F1F5F9;
            margin-bottom: 28px;
        }
        
        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #64748B;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }
        
        .tab-btn:hover {
            color: #5B4FE8;
        }
        
        .tab-btn.active {
            color: #5B4FE8;
            border-bottom-color: #5B4FE8;
        }
        
        /* ==================== CHIPS ==================== */
        .chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #E2E8F0;
            background: #fff;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .chip:hover {
            border-color: #5B4FE8;
            color: #5B4FE8;
            transform: translateY(-1px);
        }
        
        .chip-active {
            background: #5B4FE8;
            color: #fff;
            border-color: #5B4FE8;
        }
        
        /* ==================== BUTTONS ==================== */
        .btn-save {
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            color: #fff;
            padding: 12px 28px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91,79,232,0.3);
        }
        
        .btn-mentor {
            background: #5B4FE8;
            color: #fff;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-mentor:hover {
            background: #3B2BC8;
            transform: translateY(-2px);
        }
        
        /* ==================== STATUS BADGES ==================== */
        .status-warning {
            background: #FEF9C3;
            border: 1px solid #FDE68A;
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .status-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        
        .avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .avatar-initial {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background: #F1F5F9;
            margin: 24px 0;
        }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main style="padding: 32px; max-width: 900px; width: 100%;">
    
    <!-- Header -->
    <div class="animate-fadeInUp" style="margin-bottom: 28px;">
        <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
            <i class="fa-regular fa-user" style="color: #5B4FE8; margin-right: 12px;"></i>
            Mon profil
        </h1>
        <p style="color: #64748B; font-size: 14px;">
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
            <form method="POST" action="<?= APP_URL ?>/?url=profil" enctype="multipart/form-data" style="padding: 28px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_infos">
                
                <!-- Photo de profil -->
                <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 28px;">
                    <?php if (!empty($utilisateur['photo'])): ?>
                        <img src="<?= APP_URL ?>/uploads/avatars/<?= e($utilisateur['photo']) ?>" 
                             class="avatar-large" alt="Photo de profil">
                    <?php else: ?>
                        <div class="avatar-initial">
                            <?= strtoupper(substr($utilisateur['prenom'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #0F172A; margin-bottom: 6px;">
                            Photo de profil
                        </p>
                        <input type="file" name="photo" accept="image/*"
                               style="font-size: 13px; color: #64748B; margin-bottom: 6px;">
                        <p style="font-size: 12px; color: #94A3B8;">
                            <i class="fa-regular fa-image"></i> JPG, PNG, WEBP — max 2 Mo
                        </p>
                    </div>
                </div>
                
                <!-- Prénom et Nom -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
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
                    <p style="font-size: 12px; color: #94A3B8; margin-top: 6px;">
                        <i class="fa-regular fa-info-circle"></i> L'adresse email ne peut pas être modifiée
                    </p>
                </div>
                
                <div class="divider"></div>
                
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <i class="fa-regular fa-floppy-disk"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
            
            <!-- ===== BLOC DEVENIR MENTOR ===== -->
            <div style="padding: 0 28px 28px 28px;">
                <div class="divider"></div>
                
                <?php if (empty($utilisateur['est_mentor'])): ?>
                <!-- Pas encore mentor -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <p style="font-size: 15px; font-weight: 600; color: #0F172A; margin-bottom: 4px;">
                            <i class="fa-solid fa-graduation-cap" style="color: #F59E0B; margin-right: 8px;"></i>
                            Tu veux partager tes compétences ?
                        </p>
                        <p style="font-size: 13px; color: #64748B;">
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
                    <p style="font-size: 13px; color: #92400E; margin: 0; flex: 1;">
                        ⏳ Votre demande de mentorat est en cours d'examen par l'administrateur.
                    </p>
                </div>
                
                <?php else: ?>
                <!-- Mentor validé -->
                <div class="status-success">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fa-solid fa-check-circle" style="color: #166534; font-size: 20px;"></i>
                        <p style="font-size: 13px; color: #166534; margin: 0; font-weight: 500;">
                            ✓ Vous êtes mentor validé sur PeerLearn
                        </p>
                    </div>
                    <a href="<?= APP_URL ?>/?url=mentor" style="font-size: 13px; color: #166534; text-decoration: none; font-weight: 500;">
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
            <form method="POST" action="<?= APP_URL ?>/?url=profil" style="padding: 28px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_matieres">
                
                <div style="margin-bottom: 24px;">
                    <p style="font-size: 14px; color: #64748B; margin-bottom: 20px;">
                        <i class="fa-regular fa-circle-info"></i> Sélectionne les matières dans lesquelles tu souhaites recevoir de l'aide
                    </p>
                    
                    <?php foreach ($toutes_matieres as $categorie => $mats): ?>
                    <div style="margin-bottom: 24px;">
                        <p style="font-size: 11px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                            <?= e($categorie) ?>
                        </p>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                            <?php foreach ($mats as $mat): ?>
                            <label style="cursor: pointer;">
                                <input type="checkbox" name="matieres[]" value="<?= $mat['id'] ?>"
                                       <?= in_array($mat['id'], $ids_etudiant) ? 'checked' : '' ?>
                                       style="display: none;"
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
                
                <div style="display: flex; justify-content: flex-end;">
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
            <form method="POST" action="<?= APP_URL ?>/?url=profil" style="padding: 28px;">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_mdp">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-key" style="margin-right: 6px;"></i> Ancien mot de passe
                    </label>
                    <input type="password" name="ancien_mdp" class="form-input" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
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
                
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-shield-haltered"></i> Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</main>

</div><!-- ferme main-content-wrapper -->

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