<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Apprentissage peer-to-peer entre étudiants</title>
    <meta name="description" content="Plateforme de mentorat entre étudiants. Apprenez et enseignez en toute confiance avec PeerLearn.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-slideInLeft { animation: slideInLeft 0.8s ease-out forwards; }
        .animate-slideInRight { animation: slideInRight 0.8s ease-out forwards; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        
        /* ==================== CARROUSEL AVIS INFINI ==================== */
        .reviews-section {
            padding: 100px 0;
            background: linear-gradient(180deg, transparent, rgba(26, 7, 235, 0.04), transparent);
            overflow: hidden;
        }
        .reviews-header {
            max-width: 1200px; margin: 0 auto 60px;
            padding: 0 24px;
        }
        .track-wrapper { overflow: hidden; position: relative; }
        .track-wrapper::before,
        .track-wrapper::after {
            content: '';
            position: absolute; top: 0; bottom: 0; width: 180px;
            z-index: 2; pointer-events: none;
        }
        .track-wrapper::before { left: 0; background: linear-gradient(90deg, #F9FAFB, transparent); }
        .track-wrapper::after  { right: 0; background: linear-gradient(-90deg, #F9FAFB, transparent); }
        .dark .track-wrapper::before { background: linear-gradient(90deg, #111827, transparent); }
        .dark .track-wrapper::after  { background: linear-gradient(-90deg, #111827, transparent); }
        
        .track {
            display: flex; gap: 20px;
            width: max-content;
            animation: scroll-left 35s linear infinite;
        }
        .track:hover { animation-play-state: paused; }
        .track-2 {
            display: flex; gap: 20px;
            width: max-content;
            animation: scroll-right 42s linear infinite;
            margin-top: 20px;
        }
        .track-2:hover { animation-play-state: paused; }
        
        @keyframes scroll-left { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        @keyframes scroll-right { from { transform: translateX(-50%); } to { transform: translateX(0); } }
        
        .review-card {
            width: 340px; flex-shrink: 0;
            padding: 28px;
            border-radius: 20px;
            border: 1px solid #E5E7EB;
            background: #FFFFFF;
            transition: all .3s ease;
        }
        .dark .review-card { background: #1F2937; border-color: #374151; }
        .review-card:hover {
            border-color: rgba(91,79,232,.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(91,79,232,.12);
        }
        .review-stars { display: flex; gap: 3px; margin-bottom: 16px; color: #F5A623; font-size: 17px; }
        .review-text { font-size: 14px; color: #4B5563; line-height: 1.75; margin-bottom: 20px; font-style: italic; }
        .dark .review-text { color: #D1D5DB; }
        .review-author { display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid #F3F4F6; }
        .dark .review-author { border-color: #374151; }
        .review-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; color: #fff; flex-shrink: 0; transition: transform .3s ease; }
        .review-card:hover .review-avatar { transform: scale(1.1) rotate(5deg); }
        .review-name { font-size: 14px; font-weight: 600; color: #111827; }
        .dark .review-name { color: #F9FAFB; }
        .review-role { font-size: 12px; color: #9CA3AF; margin-top: 2px; }
        
        /* ==================== MENTORS SECTION ==================== */
        .mentors-section {
            padding: 80px 0;
            background: #FFFFFF;
        }
        .dark .mentors-section { background: #111827; }
        .mentors-inner { max-width: 1200px; margin: 0 auto; padding: 0 32px; }
        
        .section-label-tag {
            font-size: 12px; font-weight: 700;
            color: #0FC4A7;
            letter-spacing: .15em;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: block;
        }
        .section-heading {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 800; line-height: 1.1;
            letter-spacing: -1px;
            color: #111827;
            margin-bottom: 12px;
        }
        .dark .section-heading { color: #F9FAFB; }
        .section-sub-text {
            font-size: 16px; color: #6B7280;
            line-height: 1.7; max-width: 520px;
            margin-bottom: 48px;
        }
        
        .mentors-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 900px) { .mentors-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 500px) { .mentors-grid { grid-template-columns: 1fr; } }
        
        .mentor-card {
            padding: 32px 24px;
            border-radius: 20px;
            border: 1px solid #E5E7EB;
            background: #fff;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: all .35s ease;
            position: relative; overflow: hidden;
        }
        .dark .mentor-card { background: #1F2937; border-color: #374151; }
        .mentor-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #5B4FE8, #0FC4A7);
            transform: scaleX(0); transform-origin: left;
            transition: transform .35s ease;
        }
        .mentor-card:hover {
            border-color: rgba(91,79,232,.35);
            transform: translateY(-10px);
            box-shadow: 0 30px 70px rgba(91,79,232,.15);
        }
        .mentor-card:hover::before { transform: scaleX(1); }
        .mentor-avatar {
            width: 72px; height: 72px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 26px; color: #fff;
            margin: 0 auto 16px;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .mentor-card:hover .mentor-avatar { transform: scale(1.1); box-shadow: 0 10px 28px rgba(0,0,0,.2); }
        .mentor-name { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px; }
        .dark .mentor-name { color: #F9FAFB; }
        .mentor-subject { font-size: 13px; font-weight: 600; color: #0FC4A7; margin-bottom: 14px; }
        .mentor-stars { display: flex; justify-content: center; gap: 2px; color: #F5A623; font-size: 15px; margin-bottom: 6px; }
        .mentor-stats { font-size: 12px; color: #9CA3AF; font-weight: 500; }
        
        .btn-voir-mentors {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 40px; border-radius: 14px;
            background: linear-gradient(135deg, #5B4FE8, #8B5CF6);
            color: #fff; font-size: 15px; font-weight: 700;
            text-decoration: none;
            transition: all .25s;
            box-shadow: 0 4px 20px rgba(91,79,232,.3);
            margin-top: 48px;
        }
        .btn-voir-mentors:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(91,79,232,.45); }
        
        /* ==================== AUTRES STYLES ==================== */
        .gradient-text {
            background: linear-gradient(135deg, #5B4FE8 0%, #8B5CF6 50%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .dark .glass { background: rgba(17, 24, 39, 0.95); }
        
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #5B4FE8, #8B5CF6); border-radius: 10px; }
    </style>
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
                <a href="<?= APP_URL ?>/?url=login" class="px-5 py-2 text-purple-600 border border-purple-600 rounded-xl hover:bg-purple-50 transition">Connexion</a>
                <a href="<?= APP_URL ?>/?url=register" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-xl hover:shadow-lg transition">Inscription</a>
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
                    <a href="<?= APP_URL ?>/?url=register" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">
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
        <div class="text-center mb-12"><h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Pourquoi choisir <span class="gradient-text">PeerLearn</span> ?</h2><p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Une plateforme conçue pour faciliter l'apprentissage collaboratif entre étudiants</p></div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition"><div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6"><svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div><h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Ressources illimitées</h3><p class="text-gray-600 dark:text-gray-400">Accédez à une bibliothèque de ressources partagées par la communauté et créez vos propres contenus.</p></div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1"><div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6"><svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div><h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Mentorat personnalisé</h3><p class="text-gray-600 dark:text-gray-400">Trouvez le mentor parfait pour vous aider dans vos études et progressez à votre rythme.</p></div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-xl transition"><div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-6"><svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Sessions flexibles</h3><p class="text-gray-600 dark:text-gray-400">Choisissez entre des sessions en ligne ou en présentiel, selon vos disponibilités et préférences.</p></div>
        </div>
    </div>
</section>

<!-- ==================== COMMENT ÇA MARCHE ==================== -->
<section id="how-it-works" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12"><h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Comment <span class="gradient-text">ça marche</span> ?</h2><p class="text-xl text-gray-600 dark:text-gray-300">Trois étapes simples pour commencer votre apprentissage</p></div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center"><div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4 relative"><span class="text-2xl font-bold text-purple-600">1</span></div><h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Inscription gratuite</h3><p class="text-gray-600 dark:text-gray-400">Créez votre compte gratuitement en quelques minutes</p></div>
            <div class="text-center"><div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4 relative"><span class="text-2xl font-bold text-purple-600">2</span></div><h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Trouvez un mentor</h3><p class="text-gray-600 dark:text-gray-400">Recherchez par matière, disponibilité et note</p></div>
            <div class="text-center"><div class="w-20 h-20 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4"><span class="text-2xl font-bold text-purple-600">3</span></div><h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Commencez à apprendre</h3><p class="text-gray-600 dark:text-gray-400">Réservez une session et progressez ensemble</p></div>
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
            <a href="<?= APP_URL ?>/?url=recherche" class="mentor-card">
                <div class="mentor-avatar" style="background:<?= $m['color'] ?>"><?= $m['initiale'] ?></div>
                <div class="mentor-name"><?= htmlspecialchars($m['nom']) ?></div>
                <div class="mentor-subject"><?= htmlspecialchars($m['matiere']) ?></div>
                <div class="mentor-stars">★★★★★</div>
                <div class="mentor-stats"><?= $m['note'] ?> · <?= $m['sessions'] ?></div>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center">
            <a href="<?= APP_URL ?>/?url=register" class="btn-voir-mentors">Voir tous les mentors →</a>
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
                <a href="<?= APP_URL ?>/?url=register" class="inline-flex items-center justify-center px-8 py-3 bg-white text-purple-600 rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">S'inscrire gratuitement <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition">En savoir plus</a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div><div class="flex items-center space-x-2 mb-4"><div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-blue-500 flex items-center justify-center"><span class="text-white font-bold">P</span></div><span class="text-xl font-bold">PeerLearn</span></div><p class="text-gray-400 text-sm">Apprentissage collaboratif entre étudiants. La plateforme qui révolutionne le mentorat.</p></div>
            <div><h4 class="font-semibold mb-4">Liens rapides</h4><ul class="space-y-2 text-gray-400 text-sm"><li><a href="#features" class="hover:text-white transition">Fonctionnalités</a></li><li><a href="#how-it-works" class="hover:text-white transition">Comment ça marche</a></li><li><a href="#testimonials" class="hover:text-white transition">Témoignages</a></li><li><a href="#mentors" class="hover:text-white transition">Mentors</a></li></ul></div>
            <div><h4 class="font-semibold mb-4">Ressources</h4><ul class="space-y-2 text-gray-400 text-sm"><li><a href="#" class="hover:text-white transition">Centre d'aide</a></li><li><a href="#" class="hover:text-white transition">Blog</a></li><li><a href="#" class="hover:text-white transition">Conditions d'utilisation</a></li></ul></div>
            <div><h4 class="font-semibold mb-4">Suivez-nous</h4><div class="flex space-x-4"><a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-facebook-f"></i></a><a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-twitter"></i></a><a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-linkedin-in"></i></a><a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-purple-600 transition"><i class="fab fa-instagram"></i></a></div></div>
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
            if (current < target) { counter.innerText = Math.ceil(current); requestAnimationFrame(updateCounter); }
            else { counter.innerText = target; }
        };
        updateCounter();
    };
    const observer = new IntersectionObserver((entries) => { entries.forEach(entry => { if (entry.isIntersecting) { animateCounter(entry.target); observer.unobserve(entry.target); } }); }, { threshold: 0.5 });
    counters.forEach(counter => observer.observe(counter));
}

// ==================== THÈME ====================
function toggleTheme() { document.body.classList.toggle('dark'); localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light'); }
function initTheme() { const savedTheme = localStorage.getItem('theme'); const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches; if (savedTheme === 'dark' || (!savedTheme && prefersDark)) { document.body.classList.add('dark'); } else { document.body.classList.remove('dark'); } }

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', () => { initTheme(); animateCounters(); });
</script>
</body>
</html>