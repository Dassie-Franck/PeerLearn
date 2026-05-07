<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluer la session — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8FAFC; }

        .eval-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #E2E8F0;
            padding: 40px;
            max-width: 520px;
            margin: 0 auto;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }

        /* Étoiles */
        .stars-wrapper {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 16px 0;
        }
        .star-label {
            cursor: pointer;
            font-size: 44px;
            color: #D1D5DB;
            transition: color .15s, transform .15s;
            line-height: 1;
        }
        .star-label:hover,
        .star-label.selected { color: #F59E0B; transform: scale(1.15); }

        .note-label {
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #F59E0B;
            min-height: 20px;
            margin-top: 8px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, #5B4FE8, #7C3AED);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(91,79,232,.35); }
        .btn-submit:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .btn-cancel {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: #fff;
            color: #64748B;
            font-size: 15px;
            font-weight: 600;
            border: 1px solid #E2E8F0;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .btn-cancel:hover { background: #F8FAFC; }

        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            font-size: 14px;
            resize: none;
            outline: none;
            transition: border-color .2s;
            font-family: inherit;
        }
        textarea:focus { border-color: #5B4FE8; box-shadow: 0 0 0 3px rgba(91,79,232,.1); }
    </style>
</head>
<body>

<?php require_once BASE_PATH . '/views/layouts/toast.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_etudiant.php'; ?>

<main class="flex-1 p-8">

    <!-- Fil d'ariane -->
    <div class="text-sm text-gray-400 mb-6 max-w-xl mx-auto" style="margin-left:auto;margin-right:auto;max-width:520px">
        <a href="<?= APP_URL ?>/mes-sessions" style="color:#5B4FE8;text-decoration:none">
            ← Mes sessions
        </a>
    </div>

    <div class="eval-card">

        <!-- En-tête mentor -->
        <div style="text-align:center;margin-bottom:32px">
            <?php if (!empty($session['mentor_photo'])): ?>
                <img src="<?= APP_URL ?>/uploads/avatars/<?= e($session['mentor_photo']) ?>"
                     style="width:80px;height:80px;border-radius:20px;object-fit:cover;margin:0 auto 16px">
            <?php else: ?>
                <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#5B4FE8,#7C3AED);
                            display:flex;align-items:center;justify-content:center;
                            font-size:32px;font-weight:800;color:#fff;margin:0 auto 16px">
                    <?= strtoupper(substr($session['mentor_nom'] ?? 'M', 0, 1)) ?>
                </div>
            <?php endif; ?>

            <h1 style="font-size:22px;font-weight:800;color:#0F172A;margin-bottom:6px">
                Évaluer votre session
            </h1>
            <p style="color:#64748B;font-size:14px">
                <strong style="color:#0F172A"><?= e($session['matiere_nom'] ?? '') ?></strong>
                avec
                <strong style="color:#5B4FE8"><?= e($session['mentor_nom'] ?? '') ?></strong>
            </p>
            <p style="color:#94A3B8;font-size:12px;margin-top:4px">
                <?php
                $ts = strtotime($session['date_session'] ?? 'now');
                $jours = ['Monday'=>'Lundi','Tuesday'=>'Mardi','Wednesday'=>'Mercredi',
                          'Thursday'=>'Jeudi','Friday'=>'Vendredi',
                          'Saturday'=>'Samedi','Sunday'=>'Dimanche'];
                $mois  = ['January'=>'janvier','February'=>'février','March'=>'mars',
                          'April'=>'avril','May'=>'mai','June'=>'juin','July'=>'juillet',
                          'August'=>'août','September'=>'septembre','October'=>'octobre',
                          'November'=>'novembre','December'=>'décembre'];
                echo ($jours[date('l',$ts)] ?? '') . ' ' . date('d',$ts) . ' ' . ($mois[date('F',$ts)] ?? '') . ' ' . date('Y',$ts);
                ?>
            </p>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="<?= APP_URL ?>/evaluer?session_id=<?= $session['id'] ?>" id="eval-form">
            <?= csrf_field() ?>

            <!-- Étoiles -->
            <div style="margin-bottom:28px">
                <p style="font-size:14px;font-weight:600;color:#374151;text-align:center;margin-bottom:12px">
                    Quelle note donnez-vous à cette session ?
                </p>

                <div class="stars-wrapper" id="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label class="star-label" data-val="<?= $i ?>" onclick="setNote(<?= $i ?>)">★</label>
                    <?php endfor; ?>
                </div>

                <input type="hidden" name="note" id="note-input" value="0">
                <p class="note-label" id="note-label"></p>
            </div>

            <!-- Commentaire -->
            <div style="margin-bottom:24px">
                <label style="display:block;font-size:14px;font-weight:600;color:#374151;margin-bottom:8px">
                    Commentaire
                    <span style="font-weight:400;color:#94A3B8">(optionnel)</span>
                </label>
                <textarea name="commentaire" rows="4" maxlength="1000"
                          placeholder="Pédagogie, clarté, ponctualité... partagez votre expérience !"
                          oninput="document.getElementById('cc').textContent=this.value.length"></textarea>
                <p style="font-size:12px;color:#94A3B8;text-align:right;margin-top:4px">
                    <span id="cc">0</span>/1000
                </p>
            </div>

            <!-- Boutons -->
            <div style="display:flex;flex-direction:column;gap:10px">
                <button type="submit" class="btn-submit" id="btn-submit" disabled>
                    <i class="fa-solid fa-star"></i>
                    Envoyer l'évaluation
                </button>
                <a href="<?= APP_URL ?>/mes-sessions" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</main>
</div>

<script>
const labels = {1:'Très décevant',2:'Décevant',3:'Correct',4:'Bien',5:'Excellent !'};
let noteActuelle = 0;

function setNote(val) {
    noteActuelle = val;
    document.getElementById('note-input').value = val;
    document.getElementById('note-label').textContent = labels[val] ?? '';
    document.getElementById('btn-submit').disabled = false;
    updateStars(val);
}

function updateStars(val) {
    document.querySelectorAll('.star-label').forEach(s => {
        const v = parseInt(s.dataset.val);
        s.classList.toggle('selected', v <= val);
    });
}

// Hover preview
document.querySelectorAll('.star-label').forEach(s => {
    s.addEventListener('mouseenter', () => updateStars(parseInt(s.dataset.val)));
    s.addEventListener('mouseleave', () => updateStars(noteActuelle));
});
</script>

</body>
</html>
