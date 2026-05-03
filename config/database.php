<?php
// ============================================================
//  config/database.php - Version avec fichier .env
// ============================================================

// Fonction pour charger le fichier .env manuellement
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Extraire la variable et sa valeur
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Supprimer les guillemets si présents
            if (strpos($value, '"') === 0 || strpos($value, "'") === 0) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Charger le fichier .env (chemin absolu ou relatif)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    loadEnv($envFile);
}

// ============================================================
//  Connexion PDO
// ============================================================

function get_pdo() {
    static $pdo = null;
    
    if ($pdo === null) {
        // Récupérer les identifiants depuis les variables d'environnement
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME') ?: 'peerlearn';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    
    return $pdo;
}