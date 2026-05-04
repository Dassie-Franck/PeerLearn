<?php
// ============================================================
//  config/app.php
// ============================================================

define('APP_NAME',  'PeerLearn');
define('APP_URL',   'http://localhost/peerlearn/public');
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');

define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}