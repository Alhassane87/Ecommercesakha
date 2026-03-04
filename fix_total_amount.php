<?php
// Modifier la colonne total_amount pour accepter NULL

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Modifier total_amount pour accepter NULL
    $pdo->exec("ALTER TABLE orders MODIFY COLUMN total_amount DECIMAL(10, 2) NULL DEFAULT 0");
    echo "✅ Colonne total_amount modifiée (NULL DEFAULT 0)\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
