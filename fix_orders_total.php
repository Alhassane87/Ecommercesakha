<?php
// Ajouter la colonne total à la table orders

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la colonne total existe
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'total'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN total DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER user_id");
        echo "✅ Colonne total ajoutée à la table orders\n";
    } else {
        echo "ℹ️ Colonne total existe déjà\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
