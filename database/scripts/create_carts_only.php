<?php
// Créer uniquement la table carts sans toucher aux autres données

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la table existe déjà
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='carts'");
    if ($result->fetch()) {
        echo "Table carts existe déjà\n";
        exit(0);
    }
    
    // Créer uniquement la table carts
    $pdo->exec("CREATE TABLE carts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER UNIQUE,
        session_id VARCHAR(255),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "✅ Table carts créée avec succès\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
