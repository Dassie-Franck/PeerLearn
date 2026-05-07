<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Apprentissage peer-to-peer entre étudiants</title>
    <meta name="description" content="Plateforme de mentorat entre étudiants. Apprenez et enseignez en toute confiance avec PeerLearn.">

    <!-- Thème appliqué AVANT le rendu -->
    <script>
        (function() {
            var saved = localStorage.getItem('peerlearn-theme');
            var prefer = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            var theme = saved || prefer;
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar">
    <div class="navbar-inner">
<a href="<?= APP_URL ?>/" class="flex items-center gap-2 no-underline group">

    <!-- Logo carré -->
    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-500
                flex items-center justify-center shadow-sm
                group-hover:scale-105 transition-all duration-200">
        <span class="text-white font-bold text-sm">P</span>
    </div>

    <!-- Texte -->
    <span class="text-lg font-bold text-violet-500">
        eerLearn
    </span>

</a>

        <div class="nav-links">
            <a href="#features">Fonctionnalités</a>
            <a href="#how-it-works">Comment ça marche</a>
            <a href="#testimonials">Témoignages</a>
            <a href="#mentors">Mentors</a>
        </div>

        <div style="display:flex;align-items:center;gap:12px">
            <a href="<?= APP_URL ?>/?url=login" class="btn-connexion">Connexion</a>
            <a href="<?= APP_URL ?>/?url=register" class="btn-inscription">Inscription</a>
            <button class="btn-theme" onclick="toggleTheme()" aria-label="Changer le thème">
                <svg class="icon-moon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="icon-sun" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
        </div>
    </div>
</nav>

<!-- ==================== HERO ==================== -->
<section class="hero">
    <div class="hero-blob-1"></div>
    <div class="hero-blob-2"></div>

    <div class="hero-inner">
        <div class="animate-slideInLeft">
            <div class="hero-badge"> La révolution de l'apprentissage</div>
            <h1 class="hero-title">
                Apprenez et<br>
                <span class="gradient-text">enseignez</span> entre<br>
                étudiants
            </h1>
            <p class="hero-subtitle">
                Rejoignez une communauté où chaque étudiant peut partager ses connaissances
                et progresser ensemble. Mentorat peer-to-peer, sessions en ligne ou en présentiel.
            </p>
            <div class="hero-cta">
                <a href="<?= APP_URL ?>/?url=register" class="btn-primary-cta">
                    Commencer gratuitement
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#how-it-works" class="btn-secondary-cta">En savoir plus</a>
            </div>
        </div>

        <div class="animate-slideInRight satisfaction-card">
            <div class="satisfaction-icon">
                <svg width="24" height="24" fill="none" stroke="#0FC4A7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="satisfaction-label">Taux de satisfaction</p>
                <p class="satisfaction-value">98%</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATISTIQUES ==================== -->
<section class="stats-banner">
    <div class="stats-inner">
        <div>
            <div class="stat-number"><span class="counter" data-target="5000">0</span>+</div>
            <p class="stat-label">Étudiants actifs</p>
        </div>
        <div>
            <div class="stat-number"><span class="counter" data-target="850">0</span>+</div>
            <p class="stat-label">Mentors certifiés</p>
        </div>
        <div>
            <div class="stat-number"><span class="counter" data-target="12500">0</span>+</div>
            <p class="stat-label">Sessions réalisées</p>
        </div>
        <div>
            <div class="stat-number"><span class="counter" data-target="45">0</span></div>
            <p class="stat-label">Matières disponibles</p>
        </div>
    </div>
</section>

<!-- ==================== FONCTIONNALITÉS ==================== -->
<section id="features" class="features-section">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:56px">
            <h2 class="section-title">
                Pourquoi choisir <span class="gradient-text">PeerLearn</span> ?
            </h2>
            <p class="section-desc">
                Une plateforme conçue pour faciliter l'apprentissage collaboratif entre étudiants
            </p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg width="26" height="26" fill="none" stroke="#5B4FE8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="feature-title">Ressources illimitées</h3>
                <p class="feature-text">
                    Accédez à une bibliothèque de ressources partagées par la communauté et créez vos propres contenus.
                </p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg width="26" height="26" fill="none" stroke="#5B4FE8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </div>
                <h3 class="feature-title">Mentorat personnalisé</h3>
                <p class="feature-text">
                    Trouvez le mentor parfait pour vous aider dans vos études et progressez à votre rythme.
                </p>
            </div>
            <div class="feature-card">
                <div class="feature-icon-wrap">
                    <svg width="26" height="26" fill="none" stroke="#5B4FE8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Sessions flexibles</h3>
                <p class="feature-text">
                    Choisissez entre des sessions en ligne ou en présentiel, selon vos disponibilités et préférences.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COMMENT ÇA MARCHE ==================== -->
<section id="how-it-works" class="how-section">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:56px">
            <h2 class="section-title">
                Comment <span class="gradient-text">ça marche</span> ?
            </h2>
            <p class="section-desc">Trois étapes simples pour commencer votre apprentissage</p>
        </div>
        <div class="steps-grid">
            <div>
                <div class="step-num">1</div>
                <h3 class="step-title">Inscription gratuite</h3>
                <p class="step-text">Créez votre compte gratuitement en quelques minutes</p>
            </div>
            <div>
                <div class="step-num">2</div>
                <h3 class="step-title">Trouvez un mentor</h3>
                <p class="step-text">Recherchez par matière, disponibilité et note</p>
            </div>
            <div>
                <div class="step-num">3</div>
                <h3 class="step-title">Commencez à apprendre</h3>
                <p class="step-text">Réservez une session et progressez ensemble</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CARROUSEL DES AVIS ==================== -->
<section class="reviews-section" id="testimonials">
    <div class="reviews-header">
        <span class="section-label-tag">Témoignages</span>
        <h2 class="section-heading">Ce qu'ils en <span class="gradient-text">pensent</span></h2>
        <p class="section-sub-text">
            Des centaines d'étudiants ont déjà transformé leur façon d'apprendre.
        </p>
    </div>

    <?php
    $avis_1 = [
        ['note'=>5,'texte'=>"Mon mentor m'a expliqué les algorithmes en 1h là où j'avais bloqué pendant 2 semaines. Je recommande à 100%.",'nom'=>'Amine K.','role'=>'Étudiant en L3 Info','color'=>'#5B4FE8'],
        ['note'=>5,'texte'=>"La plateforme est super intuitive, j'ai trouvé un mentor disponible le lendemain de mon inscription. Incroyable.",'nom'=>'Sara M.','role'=>'Master 1 Finance','color'=>'#0FC4A7'],
        ['note'=>5,'texte'=>"J'avais peur d'être jugée pour mes lacunes, mais mon mentor était bienveillant et très pédagogue. Super expérience.",'nom'=>'Fatou D.','role'=>'Licence 2 Droit','color'=>'#F5A623'],
        ['note'=>5,'texte'=>"J'ai réussi mon partiel grâce à 3 sessions avec mon mentor en Maths. La meilleure décision de l'année.",'nom'=>'Thomas B.','role'=>'Prépa scientifique','color'=>'#EF4444'],
        ['note'=>5,'texte'=>"Le système de réservation est ultra simple. En 30 secondes c'était fait. La session était parfaite.",'nom'=>'Léa R.','role'=>'BTS Comptabilité','color'=>'#8B5CF6'],
        ['note'=>4,'texte'=>"Très bonne expérience globale. Mon mentor était disponible et répondait même à mes questions entre les sessions.",'nom'=>'Moussa T.','role'=>'Master 2 Économie','color'=>'#06B6D4'],
    ];
    $avis_2 = [
        ['note'=>5,'texte'=>"Devenir mentor sur PeerLearn m'a permis de consolider mes propres connaissances tout en aidant d'autres étudiants.",'nom'=>'Clara V.','role'=>'Mentor · M2 Psychologie','color'=>'#EC4899'],
        ['note'=>5,'texte'=>"Interface claire, paiement rapide, étudiants motivés. C'est une vraie valeur ajoutée dans mon parcours.",'nom'=>'Karim A.','role'=>'Mentor · Doctorat Physique','color'=>'#10B981'],
        ['note'=>5,'texte'=>"J'ai pu débloquer ma compréhension de la comptabilité en 2 sessions. Valait largement l'investissement.",'nom'=>'Julie P.','role'=>'IUT Gestion','color'=>'#F59E0B'],
        ['note'=>5,'texte'=>"Mon mentor a adapté ses explications à mon niveau sans jamais me faire sentir inférieur. Très professionnel.",'nom'=>'David N.','role'=>'Licence 1 Médecine','color'=>'#3B82F6'],
        ['note'=>5,'texte'=>"La fonctionnalité de messagerie m'a permis de poser mes questions avant la session. Gain de temps énorme.",'nom'=>'Nadia O.','role'=>'BTS Marketing','color'=>'#6366F1'],
        ['note'=>4,'texte'=>"Plateforme sérieuse et fiable. Les mentors sont vraiment compétents et les sessions très enrichissantes.",'nom'=>'Pierre L.','role'=>'Licence 3 Maths','color'=>'#14B8A6'],
    ];

    $render_track = function(array $avis, string $extra_class) {
        $double = array_merge($avis, $avis);
        echo "<div class='track-wrapper'><div class='track $extra_class'>";
        foreach ($double as $a) {
            $stars = str_repeat('★', $a['note']) . str_repeat('☆', 5 - $a['note']);
            $initiale = strtoupper(substr($a['nom'], 0, 1));
            echo "<div class='review-card'>
                <div class='review-stars'>{$stars}</div>
                <p class='review-text'>« " . htmlspecialchars($a['texte']) . " »</p>
                <div class='review-author'>
                    <div class='review-avatar' style='background:{$a['color']}'>{$initiale}</div>
                    <div>
                        <div class='review-name'>" . htmlspecialchars($a['nom']) . "</div>
                        <div class='review-role'>" . htmlspecialchars($a['role']) . "</div>
                    </div>
                </div>
            </div>";
        }
        echo "</div></div>";
    };

    $render_track($avis_1, '');
    $render_track($avis_2, 'track-2');
    ?>
</section>

<!-- ==================== MENTORS ==================== -->
<section class="mentors-section" id="mentors">
    <div class="mentors-inner">
        <span class="section-label-tag">Notre communauté</span>
        <h2 class="section-heading">Des mentors <span class="gradient-text">d'exception</span></h2>
        <p class="section-sub-text">
            Tous vérifiés, tous passionnés. Trouvez celui qui correspond à vos besoins.
        </p>

        <div class="mentors-grid">
            <?php
            $mentors = [
                ['initiale'=>'A','nom'=>'Awa S.','matiere'=>'Mathématiques','note'=>'5.0','sessions'=>'48 sessions','color'=>'#5B4FE8'],
                ['initiale'=>'R','nom'=>'Romain D.','matiere'=>'Algorithmique','note'=>'4.9','sessions'=>'62 sessions','color'=>'#0FC4A7'],
                ['initiale'=>'M','nom'=>'Mia T.','matiere'=>'Comptabilité','note'=>'4.8','sessions'=>'35 sessions','color'=>'#F5A623'],
                ['initiale'=>'Y','nom'=>'Yassine B.','matiere'=>'Physique','note'=>'4.9','sessions'=>'57 sessions','color'=>'#EF4444'],
            ];
            foreach ($mentors as $m): ?>
            <a href="<?= APP_URL ?>/?url=recherche" class="mentor-card">
                <div class="mentor-avatar" style="background:<?= $m['color'] ?>">
                    <?= $m['initiale'] ?>
                </div>
                <div class="mentor-name"><?= htmlspecialchars($m['nom']) ?></div>
                <div class="mentor-subject"><?= htmlspecialchars($m['matiere']) ?></div>
                <div class="mentor-stars">★★★★★</div>
                <div class="mentor-stats"><?= $m['note'] ?> · <?= $m['sessions'] ?></div>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center">
            <a href="<?= APP_URL ?>/?url=register" class="btn-voir-mentors">
                Voir tous les mentors →
            </a>
        </div>
    </div>
</section>

<!-- ==================== CTA FINAL ==================== -->
<section class="cta-section">
    <div class="cta-box">
        <h2 class="cta-title">Prêt à booster vos résultats ?</h2>
        <p class="cta-sub">
            Rejoignez des milliers d'étudiants qui progressent chaque jour grâce à PeerLearn.
            Inscription gratuite, sans engagement.
        </p>
        <div class="cta-buttons">
            <a href="<?= APP_URL ?>/?url=register" class="btn-cta-white">
                S'inscrire gratuitement
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="#features" class="btn-cta-outline">En savoir plus</a>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                <div class="footer-logo">
                    <span>P</span>
                </div>
                <span style="font-size:18px;font-weight:700;color:#fff">PeerLearn</span>
            </div>
            <p>Apprentissage collaboratif entre étudiants. La plateforme qui révolutionne le mentorat.</p>
        </div>
        <div class="footer-col">
            <h4>Liens rapides</h4>
            <ul>
                <li><a href="#features">Fonctionnalités</a></li>
                <li><a href="#how-it-works">Comment ça marche</a></li>
                <li><a href="#testimonials">Témoignages</a></li>
                <li><a href="#mentors">Mentors</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Ressources</h4>
            <ul>
                <li><a href="#">Centre d'aide</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Conditions d'utilisation</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Suivez-nous</h4>
            <div class="social-links">
                <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?= date('Y') ?> PeerLearn. Tous droits réservés.
    </div>
</footer>

<!-- ==================== JAVASCRIPT ==================== -->
<script>
// Thème
function toggleTheme() {
    var html = document.documentElement;
    var current = html.getAttribute('data-theme');
    var next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('peerlearn-theme', next);
}

// Compteurs animés
function animateCounter(el) {
    var target = parseInt(el.getAttribute('data-target'));
    var start = 0;
    var duration = 2000;
    var steps = 80;
    var increment = target / steps;
    var step = 0;

    var timer = setInterval(function() {
        step++;
        start += increment;
        if (step >= steps) {
            el.textContent = target.toLocaleString('fr-FR');
            clearInterval(timer);
        } else {
            el.textContent = Math.ceil(start).toLocaleString('fr-FR');
        }
    }, duration / steps);
}

var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting && !entry.target.dataset.animated) {
            entry.target.dataset.animated = 'true';
            animateCounter(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.counter').forEach(function(el) {
    observer.observe(el);
});
</script>

</body>
</html>
