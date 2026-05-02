<?php
// Désactiver le mode strict de MySQL pour la table orders

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si total_amount existe, sinon l'ajouter avec NULL
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'total_amount'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10, 2) NULL DEFAULT 0 AFTER total");
        echo "✅ Colonne total_amount ajoutée (NULL DEFAULT 0)\n";
    } else {
        // Modifier pour accepter NULL
        $pdo->exec("ALTER TABLE orders MODIFY COLUMN total_amount DECIMAL(10, 2) NULL DEFAULT 0");
        echo "✅ Colonne total_amount modifiée (NULL DEFAULT 0)\n";
    }
    
    // Désactiver le mode strict pour cette session
    $pdo->exec("SET sql_mode = ''");
    echo "✅ Mode strict MySQL désactivé\n";
    
    echo "\n🎉 Le problème devrait être résolu !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
