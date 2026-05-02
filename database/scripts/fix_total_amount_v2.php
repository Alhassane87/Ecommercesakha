<?php
// Supprimer et recréer la colonne total_amount correctement

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Supprimer la colonne si elle existe
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'total_amount'");
    if ($result->rowCount() > 0) {
        $pdo->exec("ALTER TABLE orders DROP COLUMN total_amount");
        echo "✅ Ancienne colonne total_amount supprimée\n";
    }
    
    // Recréer la colonne avec NULL et DEFAULT 0
    $pdo->exec("ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10, 2) NULL DEFAULT 0 AFTER total");
    echo "✅ Colonne total_amount recréée (NULL DEFAULT 0)\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
