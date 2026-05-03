<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs — Admin <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .admin-container { display:flex; min-height:100vh; background:#F8FAFC; }
        .sidebar {
            width:280px; background:linear-gradient(180deg,#0F172A 0%,#1E293B 100%);
            color:#fff; position:fixed; left:0; top:0; bottom:0; z-index:40; overflow-y:auto;
        }
        .sidebar-header { padding:24px 20px; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar-nav    { padding:20px 16px; }
        .nav-item {
            display:flex; align-items:center; gap:12px; padding:12px 16px; margin:4px 0;
            border-radius:12px; color:rgba(255,255,255,.7); transition:all .2s; text-decoration:none;
        }
        .nav-item:hover  { background:rgba(255,255,255,.1); color:#fff; }
        .nav-item.active { background:linear-gradient(135deg,#5B4FE8,#7C3AED); color:#fff; box-shadow:0 4px 12px rgba(91,79,232,.3); }
        .nav-item i { width:24px; font-size:18px; }
        .main-content { margin-left:280px; flex:1; padding:28px 32px; }

        /* Onglets filtre */
        .filter-tabs { display:flex; gap:8px; flex-wrap:wrap; }
        .filter-tab {
            padding:8px 18px; border-radius:10px; font-size:13px; font-weight:600;
            text-decoration:none; transition:all .2s; border:1px solid #E2E8F0;
            color:#64748B; background:#fff;
        }
        .filter-tab:hover  { border-color:#5B4FE8; color:#5B4FE8; }
        .filter-tab.active { background:#5B4FE8; color:#fff; border-color:#5B4FE8; }
        .filter-tab .count {
            display:inline-flex; align-items:center; justify-content:center;
            width:20px; height:20px; border-radius:50%; font-size:11px;
            background:rgba(255,255,255,.25); margin-left:6px;
        }
        .filter-tab:not(.active) .count { background:#F1F5F9; color:#64748B; }

        /* Table */
        .card { background:#fff; border-radius:20px; border:1px solid #E2E8F0; overflow:hidden; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th {
            text-align:left; padding:13px 20px; color:#64748B; font-weight:600;
            font-size:11px; text-transform:uppercase; letter-spacing:.5px;
            background:#F8FAFC; border-bottom:1px solid #E2E8F0;
        }
        .data-table td { padding:14px 20px; color:#334155; font-size:13px; border-bottom:1px solid #F1F5F9; }
        .data-table tr { transition:background .15s; }
        .data-table tr:hover { background:#F8FAFC; }
        .data-table tr:last-child td { border-bottom:none; }

        /* Badges */
        .badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-success { background:#E8F5E9; color:#2E7D32; }
        .badge-warning { background:#FFF3E0; color:#E65100; }
        .badge-error   { background:#FFEBEE; color:#C62828; }
        .badge-teal    { background:#E0F2F1; color:#00695C; }
        .badge-gray    { background:#F5F5F5; color:#616161; }
        .badge-info    { background:#E3F2FD; color:#1565C0; }

        /* Boutons action */
        .btn-sm {
            padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600;
            cursor:pointer; border:none; transition:all .2s; display:inline-flex; align-items:center; gap:5px;
        }
        .btn-suspend  { background:#FEF2F2; color:#EF4444; border:1px solid #FEE2E2; }
        .btn-suspend:hover  { background:#FEE2E2; transform:translateY(-1px); }
        .btn-activate { background:#F0FDF4; color:#10B981; border:1px solid #D1FAE5; }
        .btn-activate:hover { background:#D1FAE5; transform:translateY(-1px); }
        .btn-validate { background:#10B981; color:#fff; }
        .btn-validate:hover { background:#059669; transform:translateY(-1px); }
        .btn-reject   { background:#FEF2F2; color:#EF4444; border:1px solid #FEE2E2; }
        .btn-reject:hover   { background:#FEE2E2; transform:translateY(-1px); }

        /* Avatar */
        .user-avatar {
            width:36px; height:36px; border-radius:10px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:14px; color:#fff;
        }

        /* Modale */
        .modal-overlay {
            position:fixed; inset:0; background:rgba(0,0,0,.4); backdrop-filter:blur(4px);
            z-index:200; display:flex; align-items:center; justify-content:center; padding:16px;
        }
        .modal-box {
            background:#fff; border-radius:20px; padding:28px; width:100%; max-width:440px;
            box-shadow:0 20px 60px rgba(0,0,0,.15);
        }

        /* Search */
        .search-box {
            display:flex; align-items:center; gap:10px; background:#fff;
            border:1px solid #E2E8F0; border-radius:12px; padding:10px 16px;
            transition:border-color .2s;
        }
        .search-box:focus-within { border-color:#5B4FE8; box-shadow:0 0 0 3px rgba(91,79,232,.1); }
        .search-box input { border:none; outline:none; font-size:14px; color:#334155; background:transparent; flex:1; }

        @media(max-width:1024px) {
            .sidebar { width:80px; }
            .sidebar .logo-text, .sidebar .nav-item span { display:none; }
            .sidebar .nav-item { justify-content:center; }
            .main-content { margin-left:80px; }
        }
        @media(max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.mobile-open { transform:translateX(0); }
            .main-content { margin-left:0; padding:16px; }
        }
        ::-webkit-scrollbar { width:8px; }
        ::-webkit-scrollbar-track { background:#F1F5F9; border-radius:10px; }
        ::-webkit-scrollbar-thumb { background:#CBD5E1; border-radius:10px; }
    </style>
</head>
<body>
<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="logo-text text-xl font-bold" style="background:linear-gradient(135deg,#fff,#94A3B8);-webkit-background-clip:text;-webkit-text-fill-color:transparent">PeerLearn</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 logo-text">Admin Dashboard</p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= APP_URL ?>/?url=admin"              class="nav-item"><i class="fa-solid fa-gauge-high"></i><span>Tableau de bord</span></a>
            <a href="<?= APP_URL ?>/?url=admin-users"        class="nav-item active"><i class="fa-solid fa-users"></i><span>Utilisateurs</span></a>
            <a href="<?= APP_URL ?>/?url=admin-signalements" class="nav-item"><i class="fa-solid fa-flag"></i><span>Signalements</span></a>
            <a href="<?= APP_URL ?>/?url=admin-matieres"     class="nav-item"><i class="fa-solid fa-book"></i><span>Matières</span></a>
            <a href="<?= APP_URL ?>/?url=admin-journal"      class="nav-item"><i class="fa-solid fa-clock-rotate-left"></i><span>Journal</span></a>
        </nav>
        <div style="position:absolute;bottom:20px;left:0;right:0;padding:16px">
            <a href="<?= APP_URL ?>/?url=logout" class="nav-item" style="color:#EF4444">
                <i class="fa-solid fa-right-from-bracket"></i><span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <!-- En-tête -->
        <div style="margin-bottom:28px">
            <h1 style="font-size:26px;font-weight:800;color:#0F172A;margin-bottom:6px">
                <i class="fa-solid fa-users" style="color:#5B4FE8;margin-right:10px"></i>
                Gestion des utilisateurs
            </h1>
            <p style="color:#64748B;font-size:14px"><?= count($utilisateurs) ?> utilisateur<?= count($utilisateurs) > 1 ? 's' : '' ?> trouvé<?= count($utilisateurs) > 1 ? 's' : '' ?></p>
        </div>

        <!-- Barre recherche + filtres -->
        <div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:24px">
            <form method="GET" action="<?= APP_URL ?>/" style="flex:1;min-width:260px">
                <input type="hidden" name="url" value="admin-users">
                <input type="hidden" name="filtre" value="<?= e($filtre) ?>">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass" style="color:#94A3B8"></i>
                    <input type="text" name="search" value="<?= e($search) ?>"
                           placeholder="Rechercher par nom, prénom, email…">
                    <?php if ($search): ?>
                    <a href="<?= APP_URL ?>/?url=admin-users&filtre=<?= $filtre ?>"
                       style="color:#94A3B8;text-decoration:none;font-size:13px">✕</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="filter-tabs">
                <?php
                $tabs = [
                    ['slug'=>'tous',               'label'=>'Tous',         'count'=>$compteurs['tous']],
                    ['slug'=>'etudiants',          'label'=>'Étudiants',    'count'=>$compteurs['etudiants']],
                    ['slug'=>'mentors',            'label'=>'Mentors',      'count'=>$compteurs['mentors']],
                    ['slug'=>'mentors_en_attente', 'label'=>'En attente',   'count'=>$compteurs['mentors_en_attente']],
                    ['slug'=>'suspendus',          'label'=>'Suspendus',    'count'=>$compteurs['suspendus']],
                ];
                foreach ($tabs as $t):
                    $url = APP_URL . '/?url=admin-users&filtre=' . $t['slug'] . ($search ? '&search=' . urlencode($search) : '');
                ?>
                <a href="<?= $url ?>" class="filter-tab <?= $filtre === $t['slug'] ? 'active' : '' ?>">
                    <?= $t['label'] ?>
                    <span class="count"><?= (int)$t['count'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <?php if (empty($utilisateurs)): ?>
            <div style="padding:60px 20px;text-align:center">
                <i class="fa-solid fa-users-slash" style="font-size:40px;color:#CBD5E1;margin-bottom:16px"></i>
                <p style="color:#64748B;font-weight:600">Aucun utilisateur trouvé</p>
                <p style="color:#94A3B8;font-size:13px;margin-top:4px">Modifiez vos filtres ou votre recherche.</p>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Sessions</th>
                            <th>Inscription</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $u):
                            $couleurs = ['#5B4FE8','#0FC4A7','#F5A623','#EF4444','#8B5CF6','#06B6D4'];
                            $color    = $couleurs[$u['id'] % count($couleurs)];
                        ?>
                        <tr>
                            <!-- Avatar + nom -->
                            <td>
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div class="user-avatar" style="background:<?= $color ?>">
                                        <?= strtoupper(substr($u['prenom'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p style="font-weight:600;color:#0F172A"><?= e($u['prenom'] . ' ' . $u['nom']) ?></p>
                                        <p style="font-size:11px;color:#94A3B8">#<?= $u['id'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#64748B"><?= e($u['email']) ?></td>

                            <!-- Rôle -->
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge badge-info"><i class="fa-solid fa-shield-halved"></i> Admin</span>
                                <?php elseif ($u['est_mentor'] && $u['mentor_valide']): ?>
                                    <span class="badge badge-teal"><i class="fa-solid fa-graduation-cap"></i> Mentor</span>
                                <?php elseif ($u['est_mentor'] && !$u['mentor_valide']): ?>
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Mentor (attente)</span>
                                <?php else: ?>
                                    <span class="badge badge-gray"><i class="fa-solid fa-user"></i> Étudiant</span>
                                <?php endif; ?>
                            </td>

                            <td style="text-align:center;font-weight:600;color:#334155"><?= (int)$u['nb_sessions'] ?></td>
                            <td style="color:#64748B"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>

                            <!-- Statut -->
                            <td>
                                <?php if ($u['statut'] === 'suspendu'): ?>
                                    <span class="badge badge-error"><i class="fa-solid fa-ban"></i> Suspendu</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Actif</span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    <?php if ($u['role'] !== 'admin'): ?>

                                    <?php if ($u['est_mentor'] && !$u['mentor_valide']): ?>
                                    <!-- Valider mentor -->
                                    <form method="POST" action="<?= APP_URL ?>/?url=admin-valider">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="btn-sm btn-validate">
                                            <i class="fa-solid fa-check"></i> Valider
                                        </button>
                                    </form>
                                    <!-- Rejeter mentor -->
                                    <button onclick="ouvrirRejeter(<?= $u['id'] ?>, '<?= addslashes(e($u['prenom'] . ' ' . $u['nom'])) ?>')"
                                            class="btn-sm btn-reject">
                                        <i class="fa-solid fa-xmark"></i> Rejeter
                                    </button>
                                    <?php endif; ?>

                                    <!-- Suspendre / Réactiver -->
                                    <?php if ($u['statut'] === 'suspendu'): ?>
                                    <form method="POST" action="<?= APP_URL ?>/?url=admin-toggle-user">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <input type="hidden" name="action" value="reactiver">
                                        <button type="submit" class="btn-sm btn-activate">
                                            <i class="fa-solid fa-circle-check"></i> Réactiver
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <button onclick="ouvrirSuspendre(<?= $u['id'] ?>, '<?= addslashes(e($u['prenom'] . ' ' . $u['nom'])) ?>')"
                                            class="btn-sm btn-suspend">
                                        <i class="fa-solid fa-ban"></i> Suspendre
                                    </button>
                                    <?php endif; ?>

                                    <?php endif; /* pas admin */ ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- MODALE SUSPENDRE -->
<div id="modal-suspendre" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
            <div style="width:42px;height:42px;border-radius:12px;background:#FEF2F2;display:flex;align-items:center;justify-content:center">
                <i class="fa-solid fa-ban" style="color:#EF4444"></i>
            </div>
            <div>
                <h3 style="font-weight:700;color:#0F172A;font-size:16px">Suspendre le compte</h3>
                <p style="font-size:13px;color:#64748B" id="suspendre-nom">—</p>
            </div>
        </div>
        <p style="font-size:14px;color:#64748B;margin-bottom:20px;line-height:1.6">
            L'utilisateur ne pourra plus se connecter ni utiliser la plateforme jusqu'à réactivation.
        </p>
        <form method="POST" action="<?= APP_URL ?>/?url=admin-toggle-user">
            <?= csrfField() ?>
            <input type="hidden" name="user_id" id="suspendre-id">
            <input type="hidden" name="action" value="suspendre">
            <div style="display:flex;gap:10px">
                <button type="button" onclick="fermerModale('modal-suspendre')"
                        style="flex:1;padding:10px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;font-weight:600;cursor:pointer;color:#64748B">
                    Annuler
                </button>
                <button type="submit"
                        style="flex:1;padding:10px;border-radius:10px;border:none;background:#EF4444;color:#fff;font-weight:700;cursor:pointer">
                    Suspendre
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODALE REJETER -->
<div id="modal-rejeter" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
            <div style="width:42px;height:42px;border-radius:12px;background:#FEF2F2;display:flex;align-items:center;justify-content:center">
                <i class="fa-solid fa-xmark" style="color:#EF4444"></i>
            </div>
            <div>
                <h3 style="font-weight:700;color:#0F172A;font-size:16px">Rejeter la demande mentor</h3>
                <p style="font-size:13px;color:#64748B" id="rejeter-nom">—</p>
            </div>
        </div>
        <form method="POST" action="<?= APP_URL ?>/?url=admin-rejeter">
            <?= csrfField() ?>
            <input type="hidden" name="user_id" id="rejeter-id">
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">
                    Motif (optionnel)
                </label>
                <textarea name="motif" rows="3"
                          placeholder="Expliquez la raison du rejet à l'utilisateur…"
                          style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:10px;font-size:13px;resize:none;outline:none;font-family:inherit"
                          onfocus="this.style.borderColor='#5B4FE8'" onblur="this.style.borderColor='#E2E8F0'"></textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="button" onclick="fermerModale('modal-rejeter')"
                        style="flex:1;padding:10px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;font-weight:600;cursor:pointer;color:#64748B">
                    Annuler
                </button>
                <button type="submit"
                        style="flex:1;padding:10px;border-radius:10px;border:none;background:#EF4444;color:#fff;font-weight:700;cursor:pointer">
                    Rejeter la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function ouvrirSuspendre(id, nom) {
    document.getElementById('suspendre-id').value = id;
    document.getElementById('suspendre-nom').textContent = nom;
    document.getElementById('modal-suspendre').style.display = 'flex';
}
function ouvrirRejeter(id, nom) {
    document.getElementById('rejeter-id').value = id;
    document.getElementById('rejeter-nom').textContent = nom;
    document.getElementById('modal-rejeter').style.display = 'flex';
}
function fermerModale(id) {
    document.getElementById(id).style.display = 'none';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        fermerModale('modal-suspendre');
        fermerModale('modal-rejeter');
    }
});
// Fermer en cliquant l'overlay
['modal-suspendre','modal-rejeter'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) fermerModale(id);
    });
});
</script>

</body>
</html>