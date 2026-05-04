<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Apprentissage peer-to-peer entre étudiants</title>
    <meta name="description" content="Plateforme de mentorat entre étudiants. Apprenez et enseignez en toute confiance avec PeerLearn.">

    <!-- Ressources locales -->
    <link rel="stylesheet" href="<?= APP_URL ?>/css/tailwind.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/home.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white dark:bg-gray-900">

<!-- ==================== NAVIGATION ==================== -->
<nav class="fixed top-0 left-0 right-0 z-50 glass border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
                    <span class="text-white font-bold text-xl">P</span>
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
                    eer<span class="text-purple-600">Learn</span>
                </span>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 transition">Fonctionnalités</a>
                <a href="#how-it-works" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 transition">Comment ça marche</a>
                <a href="#testimonials" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 transition">Témoignages</a>
                <a href="#mentors" class="text-gray-600 dark:text-gray-300 hover:text-purple-600 transition">Mentors</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="<?= APP_URL ?>/index.php?url=login" class="px-5 py-2 text-purple-600 border border-purple-600 rounded-xl hover:bg-purple-50 transition">Connexion</a>
                <a href="<?= APP_URL ?>/register" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-xl hover:shadow-lg transition">Inscription</a>
                <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- ==================== HERO SECTION ==================== -->
<section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-white to-blue-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800"></div>
    <div class="absolute top-20 left-10 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="animate-slideInLeft">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-sm mb-6">
                    La révolution de l'apprentissage
                </div>
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-6">
                    Apprenez et
                    <span class="gradient-text">enseignez</span>
                    entre étudiants
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    Rejoignez une communauté où chaque étudiant peut partager ses connaissances et progresser ensemble.
                    Mentorat peer-to-peer, sessions en ligne ou en présentiel.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">
                        Commencer gratuitement
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 border-2 border-purple-600 text-purple-600 rounded-xl font-semibold hover:bg-purple-50 transition">
                        En savoir plus
                    </a>
                </div>
            </div>

            <div class="relative animate-slideInRight">
                <div class="relative lg:ml-10">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-purple-200 rounded-full filter blur-2xl opacity-50"></div>

                    <div class="absolute -bottom-5 -left-5 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Taux de satisfaction</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">98%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATISTIQUES ==================== -->
<section class="py-16 bg-gradient-to-r from-purple-600 to-blue-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center text-white">
                <div class="text-4xl md:text-5xl font-bold mb-2">
                    <span class="counter" data-target="5000">0</span>+
                </div>
                <p class="text-sm opacity-90">Étudiants actifs</p>
            </div>
            <div class="text-center text-white">
                <div class="text-4xl md:text-5xl font-bold mb-2">
                    <span class="counter" data-target="850">0</span>+
                </div>
                <p class="text-sm opacity-90">Mentors certifiés</p>
            </div>
            <div class="text-center text-white">
                <div class="text-4xl md:text-5xl font-bold mb-2">
                    <span class="counter" data-target="12500">0</span>+
                </div>
                <p class="text-sm opacity-90">Sessions réalisées</p>
            </div>
            <div class="text-center text-white">
                <div class="text-4xl md:text-5xl font-bold mb-2">
                    <span class="counter" data-target="45">0</span>
                </div>
                <p class="text-sm opacity-90">Matières disponibles</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FONCTIONNALITÉS ==================== -->
<section id="features" class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Pourquoi choisir <span class="gradient-text">PeerLearn</span> ?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Une plateforme conçue pour faciliter l'apprentissage collaboratif entre étudiants</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Ressources illimitées</h3>
                <p class="text-gray-600 dark:text-gray-400">Accédez à une bibliothèque de ressources partagées par la communauté et créez vos propres contenus.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Mentorat personnalisé</h3>
                <p class="text-gray-600 dark:text-gray-400">Trouvez le mentor parfait pour vous aider dans vos études et progressez à votre rythme.</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Sessions flexibles</h3>
                <p class="text-gray-600 dark:text-gray-400">Choisissez entre des sessions en ligne ou en présentiel, selon vos disponibilités et préférences.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COMMENT ÇA MARCHE ==================== -->
<section id="how-it-works" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Comment <span class="gradient-text">ça marche</span> ?</h2>
            <p class="text-xl text-gray-600 dark:text-gray-300">Trois étapes simples pour commencer votre apprentissage</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4 relative">
                    <span class="text-2xl font-bold text-purple-600">1</span>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Inscription gratuite</h3>
                <p class="text-gray-600 dark:text-gray-400">Créez votre compte gratuitement en quelques minutes</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4 relative">
                    <span class="text-2xl font-bold text-purple-600">2</span>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Trouvez un mentor</h3>
                <p class="text-gray-600 dark:text-gray-400">Recherchez par matière, disponibilité et note</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600">3</span>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Commencez à apprendre</h3>
                <p class="text-gray-600 dark:text-gray-400">Réservez une session et progressez ensemble</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CARROUSEL DES AVIS (INFINI) ==================== -->
<section class="reviews-section" id="testimonials">
    <div class="reviews-header">
        <span class="section-label-tag">Témoignages</span>
        <h2 class="section-heading">Ce qu'ils en <span class="gradient-text">pensent</span></h2>
        <p class="section-sub-text">Des centaines d'étudiants ont déjà transformé leur façon d'apprendre.</p>
    </div>

    <?php
    $avis_1 = [
        ['note'=>5, 'texte'=>"Mon mentor m'a expliqué les algorithmes en 1h là où j'avais bloqué pendant 2 semaines. Je recommande à 100%.", 'nom'=>'Amine K.', 'role'=>'Étudiant en L3 Info', 'color'=>'#5B4FE8'],
        ['note'=>5, 'texte'=>"La plateforme est super intuitive, j'ai trouvé un mentor disponible le lendemain de mon inscription. Incroyable.", 'nom'=>'Sara M.', 'role'=>'Master 1 Finance', 'color'=>'#0FC4A7'],
        ['note'=>5, 'texte'=>"J'avais peur d'être jugée pour mes lacunes, mais mon mentor était bienveillant et très pédagogue. Super expérience.", 'nom'=>'Fatou D.', 'role'=>'Licence 2 Droit', 'color'=>'#F5A623'],
        ['note'=>5, 'texte'=>"J'ai réussi mon partiel grâce à 3 sessions avec mon mentor en Maths. La meilleure décision de l'année.", 'nom'=>'Thomas B.', 'role'=>'Prépa scientifique', 'color'=>'#EF4444'],
        ['note'=>5, 'texte'=>"Le système de réservation est ultra simple. En 30 secondes c'était fait. La session était parfaite.", 'nom'=>'Léa R.', 'role'=>'BTS Comptabilité', 'color'=>'#8B5CF6'],
        ['note'=>4, 'texte'=>"Très bonne expérience globale. Mon mentor était disponible et répondait même à mes questions entre les sessions.", 'nom'=>'Moussa T.', 'role'=>'Master 2 Économie', 'color'=>'#06B6D4'],
    ];
    $avis_2 = [
        ['note'=>5, 'texte'=>"Devenir mentor sur PeerLearn m'a permis de consolider mes propres connaissances tout en aidant d'autres étudiants.", 'nom'=>'Clara V.', 'role'=>'Mentor · M2 Psychologie', 'color'=>'#EC4899'],
        ['note'=>5, 'texte'=>"Interface claire, paiement rapide, étudiants motivés. C'est une vraie valeur ajoutée dans mon parcours.", 'nom'=>'Karim A.', 'role'=>'Mentor · Doctorat Physique', 'color'=>'#10B981'],
        ['note'=>5, 'texte'=>"J'ai pu débloquer ma compréhension de la comptabilité en 2 sessions. Valait largement l'investissement.", 'nom'=>'Julie P.', 'role'=>'IUT Gestion', 'color'=>'#F59E0B'],
        ['note'=>5, 'texte'=>"Mon mentor a adapté ses explications à mon niveau sans jamais me faire sentir inférieur. Très professionnel.", 'nom'=>'David N.', 'role'=>'Licence 1 Médecine', 'color'=>'#3B82F6'],
        ['note'=>5, 'texte'=>"La fonctionnalité de messagerie m'a permis de poser mes questions avant la session. Gain de temps énorme.", 'nom'=>'Nadia O.', 'role'=>'BTS Marketing', 'color'=>'#6366F1'],
        ['note'=>4, 'texte'=>"Plateforme sérieuse et fiable. Les mentors sont vraiment compétents et les sessions très enrichissantes.", 'nom'=>'Pierre L.', 'role'=>'Licence 3 Maths', 'color'=>'#14B8A6'],
    ];

    $render_track = function(array $avis, string $cls) {
        $double = array_merge($avis, $avis);
        echo "<div class='track-wrapper'><div class='track $cls'>";
        foreach ($double as $a) {
            $stars = str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']);
            echo "<div class='review-card'>
                <div class='review-stars'>{$stars}</div>
                <p class='review-text'>« " . htmlspecialchars($a['texte']) . " »</p>
                <div class='review-author'>
                    <div class='review-avatar' style='background:{$a['color']}'>" . strtoupper(substr($a['nom'],0,1)) . "</div>
                    <div><div class='review-name'>" . htmlspecialchars($a['nom']) . "</div><div class='review-role'>" . htmlspecialchars($a['role']) . "</div></div>
                </div>
            </div>";
        }
        echo "</div></div>";
    };

    $render_track($avis_1, '');
    $render_track($avis_2, 'track-2');
    ?>
</section>

<!-- ==================== SECTION MENTORS ==================== -->
<section class="mentors-section" id="mentors">
    <div class="mentors-inner">
        <span class="section-label-tag">Notre communauté</span>
        <h2 class="section-heading">Des mentors <span class="gradient-text">d'exception</span></h2>
        <p class="section-sub-text">Tous vérifiés, tous passionnés. Trouvez celui qui correspond à vos besoins.</p>

        <div class="mentors-grid">
            <?php
            $mentors_preview = [
                ['initiale'=>'A', 'nom'=>'Awa S.', 'matiere'=>'Mathématiques', 'note'=>'5.0', 'sessions'=>'48 sessions', 'color'=>'#5B4FE8'],
                ['initiale'=>'R', 'nom'=>'Romain D.', 'matiere'=>'Algorithmique', 'note'=>'4.9', 'sessions'=>'62 sessions', 'color'=>'#0FC4A7'],
                ['initiale'=>'M', 'nom'=>'Mia T.', 'matiere'=>'Comptabilité', 'note'=>'4.8', 'sessions'=>'35 sessions', 'color'=>'#F5A623'],
                ['initiale'=>'Y', 'nom'=>'Yassine B.', 'matiere'=>'Physique', 'note'=>'4.9', 'sessions'=>'57 sessions', 'color'=>'#EF4444'],
            ];
            foreach ($mentors_preview as $m):
            ?>
            <a href="<?= APP_URL ?>/recherche" class="mentor-card">
                <div class="mentor-avatar" style="background:<?= $m['color'] ?>"><?= $m['initiale'] ?></div>
                <div class="mentor-name"><?= htmlspecialchars($m['nom']) ?></div>
                <div class="mentor-subject"><?= htmlspecialchars($m['matiere']) ?></div>
                <div class="mentor-stars">★★★★★</div>
                <div class="mentor-stats"><?= $m['note'] ?> · <?= $m['sessions'] ?></div>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center">
            <a href="<?= APP_URL ?>/register" class="btn-voir-mentors">Voir tous les mentors →</a>
        </div>
    </div>
</section>

<!-- ==================== CTA FINAL ==================== -->
<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl p-12 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Prêt à booster vos résultats ?</h2>
            <p class="text-lg opacity-90 mb-8">Rejoignez des milliers d'étudiants qui progressent chaque jour grâce à PeerLearn. Inscription gratuite, sans engagement.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-8 py-3 bg-white text-purple-600 rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">S'inscrire gratuitement <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition">En savoir plus</a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center">
                        <span class="text-white font-bold">P</span>
                    </div>
                    <span class="text-xl font-bold">PeerLearn</span>
                </div>
                <p class="text-gray-400 text-sm">Apprentissage collaboratif entre étudiants. La plateforme qui révolutionne le mentorat.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Liens rapides</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#features" class="hover:text-white transition">Fonctionnalités</a></li>
                    <li><a href="#how-it-works" class="hover:text-white transition">Comment ça marche</a></li>
                    <li><a href="#testimonials" class="hover:text-white transition">Témoignages</a></li>
                    <li><a href="#mentors" class="hover:text-white transition">Mentors</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Ressources</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Centre d'aide</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Suivez-nous</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">&copy; <?= date('Y') ?> PeerLearn. Tous droits réservés.</div>
    </div>
</footer>

<script>
// ==================== COMPTEURS ANIMÉS ====================
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        let current = 0;
        const increment = target / 50;
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.innerText = Math.ceil(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(counter => observer.observe(counter));
}

// ==================== THÈME ====================
function toggleTheme() {
    document.body.classList.toggle('dark');
    localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
}

function initTheme() {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        document.body.classList.add('dark');
    } else {
        document.body.classList.remove('dark');
    }
}

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    animateCounters();
});
</script>
</body>
</html>
