<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de mentors — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/etudiant/recherche.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="search-container">
    <?php
    /* 
     * Inclusion de la barre latérale. 
     * Assurez-vous que les variables nécessaires ($page_active, $nb_non_lus, $utilisateur) 
     * sont bien définies dans votre contrôleur avant d'inclure cette vue.
     */
    $page_active = $page_active ?? 'recherche';
    require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; 
    ?>
    
    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="search-main-content">
        
        <!-- Header -->
        <div class="animate-fadeInUp mb-7">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                <i class="fa-solid fa-magnifying-glass" style="color: #5B4FE8; margin-right: 12px;"></i>
                Trouver un mentor
            </h1>
            <p class="text-gray-500 text-sm">
                <i class="fa-regular fa-users"></i> <?= count($mentors) ?> mentor<?= count($mentors) > 1 ? 's' : '' ?> disponible<?= count($mentors) > 1 ? 's' : '' ?>
            </p>
        </div>
        
        <!-- ==================== FILTRES ==================== -->
        <form method="GET" action="<?= APP_URL ?>/" id="form-recherche" class="animate-fadeInUp">
            <input type="hidden" name="url" value="recherche">
            
            <div class="filter-card">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-5">
                        <!-- Recherche par nom -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-regular fa-user" style="margin-right: 6px;"></i> Nom du mentor
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                                <input type="text" name="nom" value="<?= htmlspecialchars($filtres['nom'] ?? '') ?>" 
                                       placeholder="ex: Dupont"
                                       class="filter-input pl-10">
                            </div>
                        </div>
                        
                        <!-- Filtre matière -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-book" style="margin-right: 6px;"></i> Matière
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-graduation-cap absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                                <select name="matiere" class="filter-select pl-10">
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
                                <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <!-- Filtre mode -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-video" style="margin-right: 6px;"></i> Mode de session
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-laptop absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                                <select name="mode" class="filter-select pl-10">
                                    <option value="">Tous les modes</option>
                                    <option value="presentiel" <?= ($filtres['mode'] ?? '') === 'presentiel' ? 'selected' : '' ?>>🏢 Présentiel</option>
                                    <option value="en_ligne" <?= ($filtres['mode'] ?? '') === 'en_ligne' ? 'selected' : '' ?>>💻 En ligne</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        
                        <!-- Note minimale -->
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fa-solid fa-star" style="margin-right: 6px;"></i> Note minimum
                            </label>
                            <div class="flex gap-2" id="stars-filter">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" data-val="<?= $i ?>" onclick="setNoteMin(<?= $i ?>)"
                                        class="star-btn border transition-all <?= ((int)($filtres['note_min'] ?? 0)) >= $i
                                                   ? 'border-amber-400 bg-amber-50 text-amber-500'
                                                   : 'border-gray-200 bg-white text-gray-300' ?>">
                                    ★
                                </button>
                                <?php endfor; ?>
                                <input type="hidden" name="note_min" id="note_min_input" value="<?= htmlspecialchars($filtres['note_min'] ?? 0) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ligne 2 : tri + boutons -->
                    <div class="flex flex-wrap items-center justify-between gap-4 pt-5 border-t border-gray-100">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-xs font-semibold text-gray-500 uppercase">
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
                        <div class="flex gap-3">
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
            <p class="text-base font-semibold text-gray-900 mb-2">Aucun mentor trouvé</p>
            <p class="text-gray-500 text-sm mb-6">Essayez d'élargir vos critères de recherche.</p>
            <a href="<?= APP_URL ?>/?url=recherche" class="btn-view-profile inline-flex">
                <i class="fa-solid fa-eye"></i> Voir tous les mentors
            </a>
        </div>
        
        <?php else: ?>
        <div class="mentors-grid animate-fadeIn">
            <?php foreach ($mentors as $m): ?>
            <div class="mentor-card">
                <!-- Avatar + infos -->
                <div class="flex items-start gap-4 mb-4">
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
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-0.5">
                            <?= htmlspecialchars($m['prenom'] ?? '') ?> <?= htmlspecialchars($m['nom'] ?? '') ?>
                        </h3>
                        <?php if (!empty($m['niveau'])): ?>
                        <p class="text-xs text-gray-500 mb-2">
                            <i class="fa-regular fa-graduation-cap"></i> <?= htmlspecialchars($m['niveau']) ?>
                        </p>
                        <?php endif; ?>
                        
                        <!-- Note -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <div class="flex gap-0.5">
                                <?php
                                $note = round((float)($m['note_moyenne'] ?? 0), 1);
                                $plein = floor($note);
                                $demi = ($note - $plein) >= 0.5;
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <?php if ($i <= $plein): ?>
                                        <span class="text-amber-400">★</span>
                                    <?php elseif ($i == $plein + 1 && $demi): ?>
                                        <span class="text-amber-400">½</span>
                                    <?php else: ?>
                                        <span class="text-gray-200">★</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                <?= $note > 0 ? number_format($note, 1) : '—' ?>
                            </span>
                            <span class="text-xs text-gray-400">
                                (<?= (int)($m['nb_evaluations'] ?? 0) ?> avis)
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Bio -->
                <?php if (!empty($m['bio'])): ?>
                <p class="line-clamp-2 text-sm text-gray-500 leading-relaxed mb-4">
                    <?= htmlspecialchars($m['bio']) ?>
                </p>
                <?php endif; ?>
                
                <!-- Matières -->
                <?php if (!empty($m['matieres_liste'])): ?>
                <div class="flex flex-wrap gap-2 mb-5">
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
                    <span class="subject-badge bg-gray-100 text-gray-500">
                        +<?= $reste ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Stats + CTA -->
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-xs text-gray-400"></i>
                        <span class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-900"><?= (int)($m['total_sessions'] ?? 0) ?></span> session<?= ((int)($m['total_sessions'] ?? 0) > 1) ? 's' : '' ?>
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
    observer.observe(el);
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>