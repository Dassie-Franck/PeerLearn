<?php
// ============================================================
//  config/app.php — Lit les constantes depuis .env
// ============================================================

// Le .env est déjà chargé par database.php (inclus AVANT app.php dans index.php)

define('APP_NAME',   getenv('APP_NAME') ?: 'PeerLearn');
define('APP_URL',    rtrim(getenv('APP_URL') ?: 'http://localhost', '/'));
define('BASE_PATH',  dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('DEBUG',      getenv('APP_DEBUG') === 'true');
define('ENV',        getenv('APP_ENV')   ?: 'local');

// ── Affichage des erreurs ────────────────────────────────────
if (DEBUG) {
    ini_set('display_errors',         1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors',         0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log',  BASE_PATH . '/logs/error.log');
}

// ── Timezone ─────────────────────────────────────────────────
date_default_timezone_set('Africa/Douala');