<?php
// Ajouter la colonne total_amount à la table orders

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier et ajouter total_amount
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'total_amount'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER total");
        echo "✅ Colonne total_amount ajoutée à la table orders\n";
    } else {
        echo "ℹ️ Colonne total_amount existe déjà\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
