<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? h($pageTitle) . ' — PeerLearn' : 'PeerLearn' ?></title>

    <!-- Tailwind CSS CDN v3 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  '#5B4FE8',
                        'primary-dark': '#4a3fd4',
                        teal:     '#0FC4A7',
                        ink:      '#0D0D14',
                        paper:    '#F7F6F2',
                    },
                    fontFamily: {
                        sans:    ['DM Sans', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="bg-paper font-sans text-ink antialiased">
