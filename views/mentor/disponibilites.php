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
        .form-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .form-card:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
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
        
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            font-size: 14px;
            transition: all 0.2s;
            background: #fff;
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #0FC4A7;
            box-shadow: 0 0 0 3px rgba(15,196,167,0.1);
        }
        
        /* Radio group */
        .radio-option {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .radio-option:hover {
            border-color: #0FC4A7;
            background: rgba(15,196,167,0.05);
        }
        
        .radio-option.active {
            border-color: #0FC4A7;
            background: rgba(15,196,167,0.1);
        }
        
        /* ==================== SLOT CARD ==================== */
        .slot-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid #E2E8F0;
            transition: all 0.3s ease;
        }
        
        .slot-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .slot-card-free {
            background: #fff;
            border-left: 4px solid #0FC4A7;
        }
        
        .slot-card-booked {
            background: #FEFCE8;
            border-left: 4px solid #EAB308;
        }
        
        .slot-time {
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
        }
        
        .btn-delete {
            background: transparent;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 8px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        
        .btn-delete:hover {
            background: #FEF2F2;
            color: #EF4444;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #0FC4A7, #0D9488);
            color: #fff;
            padding: 12px 24px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15,196,167,0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
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
        
        /* Alert messages */
        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #166534;
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            color: #991B1B;
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        /* Date group */
        .date-group {
            margin-bottom: 24px;
        }
        
        .date-header {
            font-size: 12px;
            font-weight: 700;
            color: #0FC4A7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #E2E8F0;
        }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/sidebar_mentor.php'; ?>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<main style="padding: 32px;">
    
    <!-- En-tête -->
    <div class="animate-fadeInUp" style="margin-bottom: 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                    <i class="fa-solid fa-calendar-plus" style="color: #0FC4A7; margin-right: 12px;"></i>
                    Mes disponibilités
                </h1>
                <p style="color: #64748B; font-size: 14px;">Publiez vos créneaux pour que les étudiants puissent vous réserver.</p>
            </div>
            <div class="flex gap-2 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-teal-500 inline-block"></span> Libre</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span> Réservé</span>
            </div>
        </div>
    </div>
    
    <!-- Messages flash -->
    <?php if (!empty($succes)): ?>
    <div class="animate-fadeInUp alert-success">
        <i class="fa-solid fa-check-circle"></i>
        <p style="margin: 0; font-size: 14px;"><?= e($succes) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($erreur)): ?>
    <div class="animate-fadeInUp alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <p style="margin: 0; font-size: 14px;"><?= e($erreur) ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($erreurs)): ?>
    <div class="animate-fadeInUp alert-error">
        <i class="fa-solid fa-list"></i>
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($erreurs as $e): ?>
                <li style="font-size: 14px;"><?= e($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 28px;">
        
        <!-- ==================== FORMULAIRE AJOUT ==================== -->
        <div class="animate-slideInRight">
            <div class="form-card">
                <div style="padding: 24px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-plus-circle" style="color: #0FC4A7;"></i>
                        Ajouter un créneau
                    </h2>
                    
                    <form method="POST" action="<?= APP_URL ?>/?url=disponibilites">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="ajouter">
                        
                        <!-- Matière -->
                        <div style="margin-bottom: 20px;">
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
                        <div style="margin-bottom: 20px;">
                            <label class="form-label">
                                <i class="fa-regular fa-calendar" style="margin-right: 6px;"></i> Date
                            </label>
                            <input type="date" name="date_dispo" required min="<?= date('Y-m-d') ?>" class="form-input">
                        </div>
                        
                        <!-- Horaires -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
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
                        <div style="margin-bottom: 24px;">
                            <label class="form-label">
                                <i class="fa-solid fa-video" style="margin-right: 6px;"></i> Mode de session
                            </label>
                            <div style="display: flex; gap: 12px;">
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
                <div style="padding: 24px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-list-check" style="color: #0FC4A7;"></i>
                        Créneaux à venir
                        <span style="font-size: 13px; font-weight: 500; color: #64748B; margin-left: auto;"><?= count($disponibilites) ?> créneau(x)</span>
                    </h2>
                    
                    <?php if (empty($disponibilites)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
                        </div>
                        <p style="font-size: 14px; color: #64748B; margin-bottom: 8px;">Aucun créneau publié</p>
                        <p style="font-size: 13px; color: #94A3B8;">Ajoutez votre premier créneau ci-contre</p>
                    </div>
                    <?php else: 
                        $par_date = [];
                        foreach ($disponibilites as $d) {
                            $par_date[$d['date_dispo']][] = $d;
                        }
                        ksort($par_date);
                    ?>
                    
                    <div style="max-height: 500px; overflow-y: auto; padding-right: 4px;">
                        <?php foreach ($par_date as $date => $creneaux):
                            $jours = ['Sunday'=>'Dimanche', 'Monday'=>'Lundi', 'Tuesday'=>'Mardi',
                                      'Wednesday'=>'Mercredi', 'Thursday'=>'Jeudi', 'Friday'=>'Vendredi', 'Saturday'=>'Samedi'];
                            $jour = $jours[date('l', strtotime($date))];
                        ?>
                        <div class="date-group">
                            <div class="date-header">
                                <i class="fa-regular fa-calendar"></i> <?= $jour . ' ' . date('d/m/Y', strtotime($date)) ?>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <?php foreach ($creneaux as $c): ?>
                                <div class="slot-card <?= $c['est_reservee'] ? 'slot-card-booked' : 'slot-card-free' ?>">
                                    <div style="flex: 1;">
                                        <div class="slot-time">
                                            <i class="fa-regular fa-clock" style="color: #94A3B8; font-size: 12px;"></i>
                                            <?= date('H:i', strtotime($c['heure_debut'])) ?> — <?= date('H:i', strtotime($c['heure_fin'])) ?>
                                            <span style="font-size: 13px; font-weight: 500; color: #0FC4A7;">· <?= e($c['matiere_nom']) ?></span>
                                        </div>
                                        <div style="display: flex; gap: 16px; margin-top: 6px;">
                                            <span style="font-size: 12px; color: #64748B;">
                                                <i class="fa-solid fa-<?= $c['mode_session'] === 'en_ligne' ? 'video' : 'building' ?>"></i>
                                                <?= $c['mode_session'] === 'en_ligne' ? 'En ligne' : 'Présentiel' ?>
                                            </span>
                                            <?php if ($c['est_reservee']): ?>
                                            <span style="font-size: 11px; background: #FEF3C7; color: #D97706; padding: 2px 8px; border-radius: 12px;">
                                                <i class="fa-solid fa-lock"></i> Réservé
                                            </span>
                                            <?php else: ?>
                                            <span style="font-size: 11px; background: #E8F5E9; color: #2E7D32; padding: 2px 8px; border-radius: 12px;">
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

</div><!-- ferme main-content-wrapper -->

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