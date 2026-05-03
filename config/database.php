<?php
// ============================================================
//  config/database.php
// ============================================================

function get_pdo() {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=peerlearn;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    return $pdo;
}