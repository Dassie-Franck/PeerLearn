<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — <?= APP_NAME ?></title>
    
    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/messages/inbox.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<div class="messages-container">
    <?php
    /* 
     * Inclusion de la barre latérale.
     * Variables nécessaires : $page_active, $nb_non_lus, $utilisateur
     */
    $page_active = $page_active ?? 'messages';
    require_once BASE_PATH . '/views/layouts/navbar_etudiant.php';
    ?>
    
    <!-- ══ CONTENU PRINCIPAL ════════════════════════════════════ -->
    <main class="messages-main-content">

        <!-- En-tête -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">
                    <i class="fa-regular fa-message" style="color: #5B4FE8; margin-right: 12px;"></i>
                    Messages
                </h1>
                <p class="text-gray-500 text-sm mt-1">Vos conversations avec vos mentors et étudiants.</p>
            </div>

            <div class="flex items-center gap-3">
                <?php if (($total_non_lus ?? 0) > 0): ?>
                <span class="px-3 py-1.5 rounded-full bg-violet-100 text-violet-700 text-sm font-semibold border border-violet-200">
                    <i class="fa-regular fa-envelope mr-1"></i>
                    <?= $total_non_lus ?> non lu<?= $total_non_lus > 1 ? 's' : '' ?>
                </span>
                <?php endif; ?>

                <!-- Bouton nouvelle conversation -->
                <button onclick="openModalConv()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r
                               from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700
                               text-white text-sm font-semibold transition-all shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-plus"></i>
                    Nouveau message
                </button>
            </div>
        </div>

        <!-- ══ MODAL NOUVELLE CONVERSATION ════════════════════════ -->
        <div id="modal-nouvelle-conv" class="modal-conv" onclick="closeModalConvOutside(event)">
            <div id="modal-nouvelle-conv-box" class="modal-conv-box">

                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h2 class="font-bold text-lg text-gray-900">Nouvelle conversation</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Recherchez un mentor ou un étudiant.</p>
                    </div>
                    <button onclick="closeModalConv()"
                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center
                                   justify-center text-gray-500 transition-colors">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <!-- Recherche -->
                <div class="px-6 pt-5 pb-2">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="search-user"
                               placeholder="Nom, prénom..."
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200
                                      rounded-xl text-sm text-gray-900 outline-none
                                      focus:border-violet-500 focus:bg-white transition-colors"
                               oninput="rechercherUtilisateur(this.value)">
                    </div>
                </div>

                <!-- Résultats -->
                <div id="search-results" class="px-4 pb-5 max-h-72 overflow-y-auto">
                    <!-- Suggestions par défaut : mentors disponibles -->
                    <p class="text-xs text-gray-400 px-2 py-3 font-medium">
                        <i class="fa-solid fa-star text-amber-400 mr-1"></i> Mentors disponibles
                    </p>
                    <?php
                    // Charge les mentors disponibles comme suggestions par défaut
                    $pdo = get_pdo();
                    $mentors_dispo = $pdo->query("
                        SELECT u.id, u.nom, u.prenom, u.photo, mp.note_moyenne
                        FROM utilisateurs u
                        INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
                        WHERE u.est_mentor = 1 AND u.mentor_valide = 1
                          AND u.statut = 'actif' AND mp.statut_dispo = 'disponible'
                          AND u.id != " . getUserId() . "
                        ORDER BY mp.note_moyenne DESC
                        LIMIT 8
                    ")->fetchAll();
                    ?>
                    <?php if (empty($mentors_dispo)): ?>
                    <p class="text-sm text-gray-400 text-center py-4">Aucun mentor disponible.</p>
                    <?php else: ?>
                    <div id="default-list">
                        <?php foreach ($mentors_dispo as $m): ?>
                        <a href="<?= APP_URL ?>/?url=conversation&avec=<?= $m['id'] ?>"
                           class="user-result flex items-center gap-3 px-2 py-2.5 rounded-xl
                                  hover:bg-violet-50 transition-colors">
                            <?php if (!empty($m['photo'])): ?>
                                <img src="<?= APP_URL ?>/uploads/avatars/<?= htmlspecialchars($m['photo']) ?>"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <?php else: ?>
                                <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center
                                            justify-center text-teal-600 font-bold text-sm flex-shrink-0">
                                    <?= strtoupper(substr($m['prenom'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    <?= htmlspecialchars($m['prenom']) ?> <?= htmlspecialchars($m['nom']) ?>
                                </p>
                                <p class="text-xs text-gray-400">Mentor</p>
                            </div>
                            <?php if ($m['note_moyenne'] > 0): ?>
                            <span class="text-xs text-amber-500 font-semibold flex-shrink-0">
                                ★ <?= number_format($m['note_moyenne'], 1) ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Résultats de recherche dynamiques -->
                    <div id="dynamic-list" class="hidden"></div>
                </div>
            </div>
        </div>

        <!-- ── LISTE CONVERSATIONS ── -->
        <?php if (empty($conversations)): ?>
        <div class="card">
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-message text-3xl text-gray-300"></i>
                </div>
                <h3 class="font-bold text-base text-gray-700 mb-2">Aucune conversation</h3>
                <p class="text-sm text-gray-400 max-w-xs mx-auto leading-relaxed mb-5">
                    Vos échanges avec vos mentors et étudiants apparaîtront ici.
                </p>
                <button onclick="openModalConv()" class="btn-primary">
                    <i class="fa-regular fa-pen-to-square"></i> Démarrer une conversation
                </button>
            </div>
        </div>

        <?php else: ?>
        <div class="card divide-y divide-gray-100">
            <?php foreach ($conversations as $conv):
                $est_moi = ((int)$conv['envoyeur_id'] === getUserId());
            ?>
            <a href="<?= APP_URL ?>/?url=conversation&avec=<?= $conv['interlocuteur_id'] ?>"
               class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors
                      <?= (int)$conv['non_lus'] > 0 ? 'bg-violet-50/40' : '' ?>">

                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                    <?php if (!empty($conv['photo'])): ?>
                        <img src="<?= APP_URL ?>/uploads/avatars/<?= htmlspecialchars($conv['photo']) ?>"
                             class="w-12 h-12 rounded-full object-cover" alt="">
                    <?php else: ?>
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-violet-100 to-indigo-100 
                                    flex items-center justify-center text-violet-600 font-bold text-lg">
                            <?= strtoupper(substr($conv['prenom'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ((int)$conv['non_lus'] > 0): ?>
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-violet-600
                                 text-white text-xs flex items-center justify-center font-bold shadow-sm">
                        <?= min((int)$conv['non_lus'], 9) ?><?= (int)$conv['non_lus'] > 9 ? '+' : '' ?>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Infos -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-semibold text-sm truncate
                                   <?= (int)$conv['non_lus'] > 0 ? 'text-gray-900 font-bold' : 'text-gray-700' ?>">
                            <?= htmlspecialchars($conv['nom_complet']) ?>
                            <?php if (($conv['est_mentor'] ?? false) && ($conv['mentor_valide'] ?? false)): ?>
                            <span class="ml-1 px-1.5 py-0.5 rounded text-xs bg-teal-50
                                         text-teal-600 border border-teal-100 font-medium">Mentor</span>
                            <?php endif; ?>
                        </p>
                        <span class="text-xs text-gray-400 flex-shrink-0">
                            <?php
                            $ts   = strtotime($conv['derniere_date']);
                            $auj  = strtotime('today');
                            $hier = strtotime('yesterday');
                            if ($ts >= $auj)       echo date('H:i', $ts);
                            elseif ($ts >= $hier)  echo 'Hier';
                            else                   echo date('d/m/Y', $ts);
                            ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 truncate mt-0.5">
                        <?= $est_moi ? '<span class="text-gray-500">Vous : </span>' : '' ?>
                        <?= htmlspecialchars(mb_substr($conv['dernier_message'], 0, 80)) ?>
                        <?= mb_strlen($conv['dernier_message']) > 80 ? '…' : '' ?>
                    </p>
                </div>

                <i class="fa-solid fa-chevron-right text-gray-300 text-sm flex-shrink-0"></i>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </main>
</div>

<script>
const APP_URL  = "<?= APP_URL ?>";
const USER_ID  = <?= getUserId() ?>;

// ── Modal ────────────────────────────────────────────────────
function openModalConv() {
    document.getElementById('modal-nouvelle-conv').classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('search-user').focus(), 200);
}
function closeModalConv() {
    document.getElementById('modal-nouvelle-conv').classList.remove('open');
    document.body.style.overflow = '';
    document.getElementById('search-user').value = '';
    reinitListe();
}
function closeModalConvOutside(e) {
    if (e.target === document.getElementById('modal-nouvelle-conv')) closeModalConv();
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModalConv();
});

// ── Recherche utilisateur en live ────────────────────────────
let timer = null;
function rechercherUtilisateur(q) {
    clearTimeout(timer);
    q = q.trim();
    if (q.length < 2) { reinitListe(); return; }

    timer = setTimeout(async () => {
        const resp = await fetch(
            `${APP_URL}/?url=search-user&q=${encodeURIComponent(q)}`
        );
        const data = await resp.json();

        const defaultList = document.getElementById('default-list');
        if (defaultList) defaultList.classList.add('hidden');
        
        const dyn = document.getElementById('dynamic-list');
        dyn.classList.remove('hidden');

        if (!data.length) {
            dyn.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">Aucun résultat.</p>';
            return;
        }

        dyn.innerHTML = data.map(u => `
            <a href="${APP_URL}/?url=conversation&avec=${u.id}"
               class="flex items-center gap-3 px-2 py-2.5 rounded-xl
                      hover:bg-violet-50 transition-colors">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-100 to-indigo-100 
                            flex items-center justify-center text-violet-600 font-bold text-sm flex-shrink-0">
                    ${u.initiale}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">${escapeHtml(u.nom_complet)}</p>
                    <p class="text-xs text-gray-400">${escapeHtml(u.role)}</p>
                </div>
                ${u.note ? `<span class="text-xs text-amber-500 font-semibold flex-shrink-0">★ ${u.note}</span>` : ''}
            </a>
        `).join('');
    }, 300);
}

function reinitListe() {
    const defaultList = document.getElementById('default-list');
    if (defaultList) defaultList.classList.remove('hidden');
    
    const dyn = document.getElementById('dynamic-list');
    dyn.classList.add('hidden');
    dyn.innerHTML = '';
}

// Fonction utilitaire pour échapper le HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
</body>
</html>