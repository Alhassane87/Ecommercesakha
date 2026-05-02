<?php
// Ajouter les colonnes manquantes à la table cart_items

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier et ajouter cart_id
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'cart_id'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE cart_items ADD COLUMN cart_id BIGINT UNSIGNED NOT NULL AFTER id");
        $pdo->exec("ALTER TABLE cart_items ADD INDEX idx_cart_id (cart_id)");
        echo "✅ Colonne cart_id ajoutée\n";
    } else {
        echo "ℹ️ Colonne cart_id existe déjà\n";
    }
    
    // Vérifier et ajouter product_id
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'product_id'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE cart_items ADD COLUMN product_id BIGINT UNSIGNED NOT NULL AFTER cart_id");
        $pdo->exec("ALTER TABLE cart_items ADD INDEX idx_product_id (product_id)");
        echo "✅ Colonne product_id ajoutée\n";
    } else {
        echo "ℹ️ Colonne product_id existe déjà\n";
    }
    
    // Vérifier et ajouter product_variation_id
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'product_variation_id'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE cart_items ADD COLUMN product_variation_id BIGINT UNSIGNED NULL AFTER product_id");
        echo "✅ Colonne product_variation_id ajoutée\n";
    } else {
        echo "ℹ️ Colonne product_variation_id existe déjà\n";
    }
    
    // Vérifier et ajouter selected_attributes
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'selected_attributes'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE cart_items ADD COLUMN selected_attributes JSON NULL AFTER product_variation_id");
        echo "✅ Colonne selected_attributes ajoutée\n";
    } else {
        echo "ℹ️ Colonne selected_attributes existe déjà\n";
    }
    
    echo "\n🎉 Toutes les colonnes ont été ajoutées à cart_items !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
