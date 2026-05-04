<?php
// ============================================================
//  views/mentor/disponibilites.php
//  Gestion des créneaux de disponibilité du mentor
// ============================================================

$page_active = 'disponibilites';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes disponibilités — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/mentor/disponibilites.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/sidebar_mentor.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main class="p-8">
    
    <!-- En-tête -->
    <div class="animate-fadeInUp mb-7">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    <i class="fa-solid fa-calendar-plus" style="color: #0FC4A7; margin-right: 12px;"></i>
                    Mes disponibilités
                </h1>
                <p class="text-gray-500 text-sm">Publiez vos créneaux pour que les étudiants puissent vous réserver.</p>
            </div>
            <div class="flex gap-3 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-teal-500 inline-block"></span> Libre</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span> Réservé</span>
            </div>
        </div>
    </div>
    
    <!-- Messages flash -->
    <?php if (!empty($succes)): ?>
    <div class="animate-fadeInUp alert-success">
        <i class="fa-solid fa-check-circle"></i>
        <p class="m-0 text-sm"><?= e($succes) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($erreur)): ?>
    <div class="animate-fadeInUp alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <p class="m-0 text-sm"><?= e($erreur) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($erreurs)): ?>
    <div class="animate-fadeInUp alert-error">
        <i class="fa-solid fa-list mt-0.5"></i>
        <ul class="m-0 pl-5">
            <?php foreach ($erreurs as $e): ?>
                <li class="text-sm"><?= e($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
        
        <!-- ==================== FORMULAIRE AJOUT ==================== -->
        <div class="animate-slideInRight">
            <div class="form-card">
                <div class="p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2.5">
                        <i class="fa-solid fa-plus-circle" style="color: #0FC4A7;"></i>
                        Ajouter un créneau
                    </h2>
                    
                    <form method="POST" action="<?= APP_URL ?>/?url=disponibilites">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="ajouter">
                        
                        <!-- Matière -->
                        <div class="mb-5">
                            <label class="form-label">
                                <i class="fa-solid fa-book" style="margin-right: 6px;"></i> Matière
                            </label>
                            <select name="matiere_id" required class="form-select">
                                <option value="">-- Choisir une matière --</option>
                                <?php foreach ($matieres_mentor as $mat): ?>
                                    <option value="<?= $mat['id'] ?>"><?= e($mat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Date -->
                        <div class="mb-5">
                            <label class="form-label">
                                <i class="fa-regular fa-calendar" style="margin-right: 6px;"></i> Date
                            </label>
                            <input type="date" name="date_dispo" required min="<?= date('Y-m-d') ?>" class="form-input">
                        </div>
                        
                        <!-- Horaires -->
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <label class="form-label">
                                    <i class="fa-regular fa-clock" style="margin-right: 6px;"></i> Début
                                </label>
                                <input type="time" name="heure_debut" required class="form-input">
                            </div>
                            <div>
                                <label class="form-label">
                                    <i class="fa-regular fa-clock" style="margin-right: 6px;"></i> Fin
                                </label>
                                <input type="time" name="heure_fin" required class="form-input">
                            </div>
                        </div>
                        
                        <!-- Mode de session -->
                        <div class="mb-6">
                            <label class="form-label">
                                <i class="fa-solid fa-video" style="margin-right: 6px;"></i> Mode de session
                            </label>
                            <div class="flex gap-3">
                                <label class="radio-option" onclick="selectMode(this, 'en_ligne')">
                                    <input type="radio" name="mode_session" value="en_ligne" checked style="accent-color: #0FC4A7;">
                                    <span>🌐 En ligne</span>
                                </label>
                                <label class="radio-option" onclick="selectMode(this, 'presentiel')">
                                    <input type="radio" name="mode_session" value="presentiel" style="accent-color: #0FC4A7;">
                                    <span>📍 Présentiel</span>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-plus"></i> Ajouter ce créneau
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- ==================== LISTE DES CRÉNEAUX ==================== -->
        <div class="animate-fadeInUp">
            <div class="form-card">
                <div class="p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2.5">
                        <i class="fa-solid fa-list-check" style="color: #0FC4A7;"></i>
                        Créneaux à venir
                        <span class="text-sm font-normal text-gray-500 ml-auto"><?= count($disponibilites) ?> créneau(x)</span>
                    </h2>
                    
                    <?php if (empty($disponibilites)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Aucun créneau publié</p>
                        <p class="text-sm text-gray-400">Ajoutez votre premier créneau ci-contre</p>
                    </div>
                    <?php else: 
                        $par_date = [];
                        foreach ($disponibilites as $d) {
                            $par_date[$d['date_dispo']][] = $d;
                        }
                        ksort($par_date);
                    ?>
                    
                    <div class="max-h-[500px] overflow-y-auto pr-1">
                        <?php foreach ($par_date as $date => $creneaux):
                            $jours = ['Sunday'=>'Dimanche', 'Monday'=>'Lundi', 'Tuesday'=>'Mardi',
                                      'Wednesday'=>'Mercredi', 'Thursday'=>'Jeudi', 'Friday'=>'Vendredi', 'Saturday'=>'Samedi'];
                            $jour = $jours[date('l', strtotime($date))];
                        ?>
                        <div class="date-group">
                            <div class="date-header">
                                <i class="fa-regular fa-calendar"></i> <?= $jour . ' ' . date('d/m/Y', strtotime($date)) ?>
                            </div>
                            <div class="flex flex-col gap-2.5">
                                <?php foreach ($creneaux as $c): ?>
                                <div class="slot-card <?= $c['est_reservee'] ? 'slot-card-booked' : 'slot-card-free' ?>">
                                    <div class="flex-1">
                                        <div class="slot-time">
                                            <i class="fa-regular fa-clock text-gray-400 text-xs"></i>
                                            <?= date('H:i', strtotime($c['heure_debut'])) ?> — <?= date('H:i', strtotime($c['heure_fin'])) ?>
                                            <span class="text-sm font-medium text-teal-600">· <?= e($c['matiere_nom']) ?></span>
                                        </div>
                                        <div class="flex gap-4 mt-1.5">
                                            <span class="text-xs text-gray-500">
                                                <i class="fa-solid fa-<?= $c['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                                <?= $c['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                                            </span>
                                            <?php if ($c['est_reservee']): ?>
                                            <span class="text-[11px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                                                <i class="fa-solid fa-lock"></i> Réservé
                                            </span>
                                            <?php else: ?>
                                            <span class="text-[11px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                                <i class="fa-solid fa-circle-check"></i> Disponible
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if (!$c['est_reservee']): ?>
                                    <form method="POST" action="<?= APP_URL ?>/?url=disponibilites" onsubmit="return confirm('Supprimer ce créneau ?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="dispo_id" value="<?= $c['id'] ?>">
                                        <button type="submit" class="btn-delete" title="Supprimer">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
    
</main>

<script>
// ==================== RADIO BUTTON STYLE ====================
function selectMode(element, mode) {
    const radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
    
    document.querySelectorAll('.radio-option').forEach(opt => {
        opt.classList.remove('active');
    });
    element.classList.add('active');
}

// ==================== INIT RADIO BUTTONS ====================
document.querySelectorAll('.radio-option').forEach(opt => {
    const radio = opt.querySelector('input[type="radio"]');
    if (radio && radio.checked) {
        opt.classList.add('active');
    }
    opt.addEventListener('click', function() {
        document.querySelectorAll('.radio-option').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>