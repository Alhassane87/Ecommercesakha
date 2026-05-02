<?php
// Ajouter la colonne category_id à la table promotions

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la colonne category_id existe
    $result = $pdo->query("SHOW COLUMNS FROM promotions LIKE 'category_id'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE promotions ADD COLUMN category_id BIGINT UNSIGNED NULL AFTER id");
        $pdo->exec("ALTER TABLE promotions ADD INDEX idx_category_id (category_id)");
        echo "✅ Colonne category_id ajoutée à la table promotions\n";
    } else {
        echo "ℹ️ Colonne category_id existe déjà\n";
    }
    
    // Vérifier si les colonnes starts_at et ends_at existent
    $result = $pdo->query("SHOW COLUMNS FROM promotions LIKE 'starts_at'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE promotions ADD COLUMN starts_at TIMESTAMP NULL AFTER discount_value");
        echo "✅ Colonne starts_at ajoutée\n";
    }
    
    $result = $pdo->query("SHOW COLUMNS FROM promotions LIKE 'ends_at'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE promotions ADD COLUMN ends_at TIMESTAMP NULL AFTER starts_at");
        echo "✅ Colonne ends_at ajoutée\n";
    }
    
    // Vérifier si la colonne is_active existe
    $result = $pdo->query("SHOW COLUMNS FROM promotions LIKE 'is_active'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE promotions ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER ends_at");
        echo "✅ Colonne is_active ajoutée\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
