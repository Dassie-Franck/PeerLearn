<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalements — Admin <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/admin/signalements.css">
    
    <!-- Font Awesome (CDN - pas d'alternative locale simple) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</head>
<body>
<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div style="display:flex;align-items:center;gap:10px">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-content:center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="logo-text text-xl font-bold" style="background:linear-gradient(135deg,#fff,#94A3B8);-webkit-background-clip:text;-webkit-text-fill-color:transparent">PeerLearn</span>
            </div>
            <p class="text-xs text-gray-400 mt-2 logo-text">Admin Dashboard</p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= APP_URL ?>/?url=admin"              class="nav-item"><i class="fa-solid fa-gauge-high"></i><span>Tableau de bord</span></a>
            <a href="<?= APP_URL ?>/?url=admin-users"        class="nav-item"><i class="fa-solid fa-users"></i><span>Utilisateurs</span></a>
            <a href="<?= APP_URL ?>/?url=admin-signalements" class="nav-item active">
                <i class="fa-solid fa-flag"></i><span>Signalements</span>
                <?php if (($compteurs['en_attente'] ?? 0) > 0): ?>
                <span style="background:#EF4444;color:#fff;font-size:10px;padding:2px 7px;border-radius:20px;margin-left:auto">
                    <?= (int)$compteurs['en_attente'] ?>
                </span>
                <?php endif; ?>
            </a>
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
                <i class="fa-solid fa-flag" style="color:#EF4444;margin-right:10px"></i>
                Signalements
            </h1>
            <p style="color:#64748B;font-size:14px">
                <?= (int)($compteurs['en_attente'] ?? 0) ?> signalement<?= ($compteurs['en_attente'] ?? 0) > 1 ? 's' : '' ?> en attente de traitement
            </p>
        </div>

        <!-- Onglets -->
        <div class="filter-tabs" style="margin-bottom:20px">
            <?php
            $tabs = [
                ['slug'=>'en_attente', 'label'=>'En attente', 'count'=>$compteurs['en_attente'] ?? 0],
                ['slug'=>'traite',     'label'=>'Traités',    'count'=>$compteurs['traite']     ?? 0],
                ['slug'=>'rejete',     'label'=>'Rejetés',    'count'=>$compteurs['rejete']     ?? 0],
                ['slug'=>'tous',       'label'=>'Tous',       'count'=>$compteurs['tous']       ?? 0],
            ];
            foreach ($tabs as $t):
            ?>
            <a href="<?= APP_URL ?>/?url=admin-signalements&filtre=<?= $t['slug'] ?>"
               class="filter-tab <?= $filtre === $t['slug'] ? 'active' : '' ?>">
                <?= $t['label'] ?><span class="cnt"><?= (int)$t['count'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Liste -->
        <div class="card">
            <?php if (empty($signalements)): ?>
            <div style="padding:60px 20px;text-align:center">
                <i class="fa-solid fa-flag-checkered" style="font-size:40px;color:#CBD5E1;margin-bottom:16px"></i>
                <p style="color:#64748B;font-weight:600;margin-bottom:4px">Aucun signalement</p>
                <p style="color:#94A3B8;font-size:13px">
                    <?= $filtre === 'en_attente' ? 'Tout est traité, bravo !' : 'Aucun signalement dans cette catégorie.' ?>
                </p>
            </div>
            <?php else: ?>

            <?php
            $type_cfg = [
                'message'    => ['icon'=>'💬', 'label'=>'Message',    'bg'=>'rgba(91,79,232,.1)',  'color'=>'#5B4FE8'],
                'evaluation' => ['icon'=>'⭐', 'label'=>'Évaluation', 'bg'=>'rgba(245,158,11,.1)', 'color'=>'#F59E0B'],
                'profil'     => ['icon'=>'👤', 'label'=>'Profil',     'bg'=>'rgba(15,196,167,.1)', 'color'=>'#0FC4A7'],
            ];
            foreach ($signalements as $s):
                $cfg = $type_cfg[$s['type_cible']] ?? ['icon'=>'🚩','label'=>$s['type_cible'],'bg'=>'#F5F5F5','color'=>'#616161'];
                $date = date('d/m/Y H:i', strtotime($s['created_at']));
            ?>
            <div class="sig-row">
                <div style="display:flex;gap:14px;align-items:flex-start">

                    <!-- Icône type -->
                    <div class="type-icon" style="background:<?= $cfg['bg'] ?>">
                        <?= $cfg['icon'] ?>
                    </div>

                    <!-- Contenu -->
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px">
                            <span class="badge" style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['color'] ?>">
                                <?= $cfg['label'] ?> #<?= $s['cible_id'] ?>
                            </span>
                            <?php
                            $sbadge = match($s['statut']) {
                                'en_attente' => '<span class="badge badge-warning"><i class="fa-solid fa-clock"></i> En attente</span>',
                                'traite'     => '<span class="badge badge-success"><i class="fa-solid fa-check"></i> Traité</span>',
                                'rejete'     => '<span class="badge badge-gray"><i class="fa-solid fa-xmark"></i> Rejeté</span>',
                                default      => ''
                            };
                            echo $sbadge;
                            ?>
                            <span style="font-size:12px;color:#94A3B8"><?= $date ?></span>
                        </div>

                        <!-- Motif -->
                        <p style="font-size:14px;color:#334155;line-height:1.6;margin-bottom:8px">
                            <strong>Motif :</strong> <?= e($s['motif']) ?>
                        </p>

                        <!-- Signalé par -->
                        <p style="font-size:12px;color:#64748B">
                            <i class="fa-solid fa-user" style="color:#94A3B8;margin-right:4px"></i>
                            Signalé par <strong><?= e($s['signale_par_prenom'] . ' ' . $s['signale_par_nom']) ?></strong>
                            <span style="color:#94A3B8">(<?= e($s['signale_par_email']) ?>)</span>
                        </p>

                        <!-- Traité par -->
                        <?php if (!empty($s['traite_par_nom'])): ?>
                        <p style="font-size:12px;color:#64748B;margin-top:4px">
                            <i class="fa-solid fa-shield-halved" style="color:#94A3B8;margin-right:4px"></i>
                            Traité par <strong><?= e($s['traite_par_prenom'] . ' ' . $s['traite_par_nom']) ?></strong>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <?php if ($s['statut'] === 'en_attente'): ?>
                <div style="display:flex;gap:8px;flex-shrink:0;align-items:center">
                    <form method="POST" action="<?= APP_URL ?>/?url=admin-signalements&filtre=<?= $filtre ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="sig_id" value="<?= $s['id'] ?>">
                        <input type="hidden" name="action" value="traiter">
                        <button type="submit" class="btn-sm btn-treat">
                            <i class="fa-solid fa-check"></i> Traiter
                        </button>
                    </form>
                    <form method="POST" action="<?= APP_URL ?>/?url=admin-signalements&filtre=<?= $filtre ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="sig_id" value="<?= $s['id'] ?>">
                        <input type="hidden" name="action" value="rejeter">
                        <button type="submit" class="btn-sm btn-reject">
                            <i class="fa-solid fa-xmark"></i> Rejeter
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>
</div>
</body>
</html>