<?php
// ============================================================
//  config/database.php — Chargement .env + connexion PDO
// ============================================================

function loadEnv(string $path): void {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorer les commentaires et lignes vides
        if (str_starts_with(trim($line), '#')) continue;

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) continue;

        $key   = trim($parts[0]);
        $value = trim($parts[1]);

        // Supprimer les guillemets enveloppants si présents
        if (preg_match('/^(["\']).*\1$/', $value)) {
            $value = substr($value, 1, -1);
        }

        putenv("$key=$value");
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
    }
}

// ── Charger le .env ──────────────────────────────────────────
loadEnv(dirname(__DIR__) . '/.env');

// ── Connexion PDO ─────────────────────────────────────────────
function get_pdo(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host   = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: 'peerlearn';
    $user   = getenv('DB_USER') ?: 'root';
    $pass   = getenv('DB_PASS') ?: '';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        // Affiche le détail en local, message générique en prod
        $debug = getenv('APP_DEBUG') === 'true';
        if ($debug) {
            die('<pre>❌ Erreur BDD : ' . $e->getMessage() . '</pre>');
        }
        die('Une erreur est survenue. Veuillez réessayer plus tard.');
    }

    return $pdo;
}