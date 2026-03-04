<?php
// Script pour créer la table migrations et exécuter les migrations

require __DIR__ . '/vendor/autoload.php';

// Configurer la connexion MySQL manuellement
$host = '127.0.0.1';
$port = '3306';
$database = 'sakha';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la table migrations si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table migrations créée\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
