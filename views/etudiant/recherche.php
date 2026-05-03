<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de mentors — <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
        
        /* ==================== LAYOUT SPECIFIQUE ==================== */
        .search-main-content {
            flex: 1;
            padding: 24px 32px;
        }

        /* Styles pour les cartes, filtres, etc. (inchangés) */
        /* ==================== FILTER CARD ==================== */
        .filter-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 28px;
        }
        
        .filter-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .filter-group {
            margin-bottom: 20px;
        }
        
        .filter-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .filter-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            font-size: 14px;
            transition: all 0.2s;
            background: #fff;
        }
        
        .filter-input:focus {
            outline: none;
            border-color: #5B4FE8;
            box-shadow: 0 0 0 3px rgba(91,79,232,0.1);
        }
        
        .filter-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            font-size: 14px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #5B4FE8;
            box-shadow: 0 0 0 3px rgba(91,79,232,0.1);
        }
        
        /* Star filter */
        .star-btn {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .star-btn:hover {
            transform: scale(1.1);
        }
        
        /* ==================== MENTOR CARD ==================== */
        .mentor-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            padding: 24px;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .mentor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: #5B4FE8;
        }
        
        .mentor-avatar {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            object-fit: cover;
        }
        
        .mentor-initial {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 24px;
            color: #fff;
        }
        
        .online-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            background: #10B981;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        
        .subject-badge {
            background: rgba(91,79,232,0.1);
            color: #5B4FE8;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .btn-view-profile {
            background: #5B4FE8;
            color: #fff;
            padding: 10px 20px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }
        
        .btn-view-profile:hover {
            background: #3B2BC8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91,79,232,0.3);
        }
        
        .btn-reset {
            background: #F1F5F9;
            color: #64748B;
            padding: 10px 20px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-reset:hover {
            background: #E2E8F0;
            color: #1E293B;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            color: #fff;
            padding: 10px 28px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91,79,232,0.3);
        }
        
        .sort-btn {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .sort-btn-active {
            background: #5B4FE8;
            color: #fff;
        }
        
        .sort-btn-inactive {
            background: #F1F5F9;
            color: #64748B;
        }
        
        .sort-btn-inactive:hover {
            background: #E2E8F0;
            color: #1E293B;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            background: #F1F5F9;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        /* Grid */
        .mentors-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        
        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1200px) {
            .mentors-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .search-main-content { padding: 16px; }
            .mentors-grid { grid-template-columns: 1fr; }
        }
        
        /* Line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="search-container">
    <?php
    /* 
     * Inclusion de la barre latérale. 
     * Assurez-vous que les variables nécessaires ($page_active, $nb_non_lus, $utilisateur) 
     * sont bien définies dans votre contrôleur avant d'inclure cette vue.
     */
    $page_active = $page_active ?? 'recherche'; // Définir la page active pour la sidebar
    // $nb_non_lus = $nb_non_lus ?? 0; // Déjà défini ou à récupérer depuis le contrôleur
    // $utilisateur = $utilisateur ?? []; // Déjà défini ou à récupérer
    require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; 
    ?>
    
    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="search-main-content">
        
        <!-- Header -->
        <div class="animate-fadeInUp" style="margin-bottom: 28px;">
            <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                <i class="fa-solid fa-magnifying-glass" style="color: #5B4FE8; margin-right: 12px;"></i>
                Trouver un mentor
            </h1>
            <p style="color: #64748B; font-size: 14px;">
                <i class="fa-regular fa-users"></i> <?= count($mentors) ?> mentor<?= count($mentors) > 1 ? 's' : '' ?> disponible<?= count($mentors) > 1 ? 's' : '' ?>
            </p>
        </div>
        
        <!-- ==================== FILTRES ==================== -->
        <form method="GET" action="<?= APP_URL ?>/" id="form-recherche" class="animate-fadeInUp">
            <input type="hidden" name="url" value="recherche">
            
            <div class="filter-card">
                <div style="padding: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
                        <!-- Recherche par nom -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-regular fa-user" style="margin-right: 6px;"></i> Nom du mentor
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #94A3B8;"></i>
                                <input type="text" name="nom" value="<?= htmlspecialchars($filtres['nom'] ?? '') ?>" 
                                       placeholder="ex: Dupont"
                                       class="filter-input" style="padding-left: 40px;">
                            </div>
                        </div>
                        
                        <!-- Filtre matière -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-book" style="margin-right: 6px;"></i> Matière
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-graduation-cap" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #94A3B8;"></i>
                                <select name="matiere" class="filter-select" style="padding-left: 40px; appearance: none;">
                                    <option value="">Toutes les matières</option>
                                    <?php
                                    if (isset($matieres) && is_array($matieres)):
                                        $par_categorie = [];
                                        foreach ($matieres as $mat) {
                                            $par_categorie[$mat['categorie'] ?? 'Autres'][] = $mat;
                                        }
                                        foreach ($par_categorie as $cat => $liste):
                                    ?>
                                    <optgroup label="<?= htmlspecialchars($cat) ?>">
                                        <?php foreach ($liste as $mat): ?>
                                        <option value="<?= htmlspecialchars($mat['nom']) ?>" <?= ($filtres['matiere'] ?? '') === $mat['nom'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($mat['nom']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <?php 
                                        endforeach; 
                                    endif; 
                                    ?>
                                </select>
                                <i class="fa-solid fa-chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #94A3B8; pointer-events: none;"></i>
                            </div>
                        </div>
                        
                        <!-- Filtre mode -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-video" style="margin-right: 6px;"></i> Mode de session
                            </label>
                            <div style="position: relative;">
                                <i class="fa-solid fa-laptop" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #94A3B8;"></i>
                                <select name="mode" class="filter-select" style="padding-left: 40px; appearance: none;">
                                    <option value="">Tous les modes</option>
                                    <option value="presentiel" <?= ($filtres['mode'] ?? '') === 'presentiel' ? 'selected' : '' ?>>🏢 Présentiel</option>
                                    <option value="en_ligne" <?= ($filtres['mode'] ?? '') === 'en_ligne' ? 'selected' : '' ?>>💻 En ligne</option>
                                </select>
                                <i class="fa-solid fa-chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 12px; color: #94A3B8; pointer-events: none;"></i>
                            </div>
                        </div>
                        
                        <!-- Note minimale -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-star" style="margin-right: 6px;"></i> Note minimum
                            </label>
                            <div style="display: flex; gap: 8px;" id="stars-filter">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-val="<?= $i ?>" onclick="setNoteMin(<?= $i ?>)"
                                        class="star-btn w-10 h-10 rounded-xl border transition-all text-lg font-bold
                                               <?= ((int)($filtres['note_min'] ?? 0)) >= $i
                                                   ? 'border-amber-400 bg-amber-50 text-amber-500'
                                                   : 'border-gray-200 bg-white text-gray-300 hover:border-amber-300' ?>">
                                    ★
                                </button>
                                <?php endfor; ?>
                                <input type="hidden" name="note_min" id="note_min_input" value="<?= htmlspecialchars($filtres['note_min'] ?? 0) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ligne 2 : tri + boutons -->
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; padding-top: 20px; border-top: 1px solid #F1F5F9;">
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <span style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase;">
                                <i class="fa-solid fa-arrow-down-wide-short"></i> Trier par
                            </span>
                            <?php
                            $tris = ['note' => 'Mieux notés', 'sessions' => 'Plus actifs', 'nom' => 'Nom A→Z'];
                            foreach ($tris as $val => $label):
                            ?>
                            <a href="<?= APP_URL ?>/?url=recherche&<?= http_build_query(array_merge($filtres ?? [], ['tri' => $val])) ?>"
                               class="sort-btn <?= ($filtres['tri'] ?? '') === $val ? 'sort-btn-active' : 'sort-btn-inactive' ?>">
                                <?= $label ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <div style="display: flex; gap: 12px;">
                            <?php 
                            $filtres_non_vides = array_filter($filtres ?? [], fn($v) => $v !== '' && $v !== 'note');
                            if (!empty($filtres_non_vides)): 
                            ?>
                            <a href="<?= APP_URL ?>/?url=recherche" class="btn-reset">
                                <i class="fa-solid fa-rotate-left"></i> Réinitialiser
                            </a>
                            <?php endif; ?>
                            <button type="submit" class="btn-search">
                                <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- ==================== RESULTATS ==================== -->
        <?php if (empty($mentors)): ?>
        <div class="empty-state animate-fadeIn">
            <div class="empty-icon">
                <i class="fa-solid fa-user-graduate text-3xl text-gray-400"></i>
            </div>
            <p style="font-size: 16px; font-weight: 600; color: #0F172A; margin-bottom: 8px;">Aucun mentor trouvé</p>
            <p style="color: #64748B; font-size: 14px; margin-bottom: 24px;">Essayez d'élargir vos critères de recherche.</p>
            <a href="<?= APP_URL ?>/?url=recherche" class="btn-view-profile" style="display: inline-flex;">
                <i class="fa-solid fa-eye"></i> Voir tous les mentors
            </a>
        </div>
        
        <?php else: ?>
        <div class="mentors-grid animate-fadeIn">
            <?php foreach ($mentors as $m): ?>
            <div class="mentor-card">
                <!-- Avatar + infos -->
                <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
                    <div class="relative">
                        <?php if (!empty($m['photo'])): ?>
                            <img src="<?= APP_URL ?>/uploads/avatars/<?= htmlspecialchars($m['photo']) ?>" class="mentor-avatar" alt="">
                        <?php else: ?>
                            <div class="mentor-initial">
                                <?= strtoupper(substr($m['prenom'] ?? '', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div class="online-dot"></div>
                    </div>
                    
                    <div style="flex: 1;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 2px;">
                            <?= htmlspecialchars($m['prenom'] ?? '') ?> <?= htmlspecialchars($m['nom'] ?? '') ?>
                        </h3>
                        <?php if (!empty($m['niveau'])): ?>
                        <p style="font-size: 12px; color: #64748B; margin-bottom: 8px;">
                            <i class="fa-regular fa-graduation-cap"></i> <?= htmlspecialchars($m['niveau']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Note -->
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <div style="display: flex; gap: 2px;">
                                <?php
                                $note = round((float)($m['note_moyenne'] ?? 0), 1);
                                $plein = floor($note);
                                $demi = ($note - $plein) >= 0.5;
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <?php if ($i <= $plein): ?>
                                        <span style="color: #F5A623;">★</span>
                                    <?php elseif ($i == $plein + 1 && $demi): ?>
                                        <span style="color: #F5A623;">½</span>
                                    <?php else: ?>
                                        <span style="color: #CBD5E1;">★</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span style="font-size: 13px; font-weight: 600; color: #0F172A;">
                                <?= $note > 0 ? number_format($note, 1) : '—' ?>
                            </span>
                            <span style="font-size: 11px; color: #94A3B8;">
                                (<?= (int)($m['nb_evaluations'] ?? 0) ?> avis)
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Bio -->
                <?php if (!empty($m['bio'])): ?>
                <p class="line-clamp-2" style="font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 16px;">
                    <?= htmlspecialchars($m['bio']) ?>
                </p>
                <?php endif; ?>
                
                <!-- Matières -->
                <?php if (!empty($m['matieres_liste'])): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px;">
                    <?php
                    $liste_matieres = explode(', ', $m['matieres_liste']);
                    $mats = array_slice($liste_matieres, 0, 3);
                    $reste = count($liste_matieres) - count($mats);
                    foreach ($mats as $mat):
                    ?>
                    <span class="subject-badge">
                        <?= htmlspecialchars(trim($mat)) ?>
                    </span>
                    <?php endforeach; ?>
                    <?php if ($reste > 0): ?>
                    <span class="subject-badge" style="background: #F1F5F9; color: #64748B;">
                        +<?= $reste ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Stats + CTA -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 16px; border-top: 1px solid #F1F5F9;">
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <i class="fa-regular fa-calendar" style="font-size: 12px; color: #94A3B8;"></i>
                        <span style="font-size: 12px; color: #64748B;">
                            <span style="font-weight: 600; color: #0F172A;"><?= (int)($m['total_sessions'] ?? 0) ?></span> session<?= ((int)($m['total_sessions'] ?? 0) > 1) ? 's' : '' ?>
                        </span>
                    </div>
                    <a href="<?= APP_URL ?>/?url=fiche-mentor&id=<?= $m['id'] ?>" class="btn-view-profile">
                        Voir le profil <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<script>
// ==================== FILTRE PAR ÉTOILES ====================
function setNoteMin(val) {
    const current = parseInt(document.getElementById('note_min_input').value) || 0;
    const newVal = (current === val) ? 0 : val;
    document.getElementById('note_min_input').value = newVal;

    document.querySelectorAll('.star-btn').forEach(btn => {
        const v = parseInt(btn.dataset.val);
        if (v <= newVal) {
            btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-300');
            btn.classList.add('border-amber-400', 'bg-amber-50', 'text-amber-500');
        } else {
            btn.classList.remove('border-amber-400', 'bg-amber-50', 'text-amber-500');
            btn.classList.add('border-gray-200', 'bg-white', 'text-gray-300');
        }
    });

    document.getElementById('form-recherche').submit();
}

// ==================== AUTO SUBMIT SUR SELECT ====================
document.querySelectorAll('select[name="matiere"], select[name="mode"]').forEach(sel => {
    sel.addEventListener('change', () => {
        document.getElementById('form-recherche').submit();
    });
});

// ==================== ANIMATION AU SCROLL ====================
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.mentor-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.5s ease';
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>