<?php
// Ajouter les colonnes manquantes qty et unit_price à cart_items

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier et ajouter qty (à la place de quantity ou en plus)
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'qty'");
    if ($result->rowCount() == 0) {
        // Renommer quantity en qty si quantity existe
        $result2 = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'quantity'");
        if ($result2->rowCount() > 0) {
            $pdo->exec("ALTER TABLE cart_items CHANGE COLUMN quantity qty INT NOT NULL DEFAULT 1");
            echo "✅ Colonne quantity renommée en qty\n";
        } else {
            $pdo->exec("ALTER TABLE cart_items ADD COLUMN qty INT NOT NULL DEFAULT 1 AFTER selected_attributes");
            echo "✅ Colonne qty ajoutée\n";
        }
    } else {
        echo "ℹ️ Colonne qty existe déjà\n";
    }
    
    // Vérifier et ajouter unit_price
    $result = $pdo->query("SHOW COLUMNS FROM cart_items LIKE 'unit_price'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE cart_items ADD COLUMN unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER qty");
        echo "✅ Colonne unit_price ajoutée\n";
    } else {
        echo "ℹ️ Colonne unit_price existe déjà\n";
    }
    
    echo "\n🎉 Toutes les colonnes cart_items sont maintenant correctes !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
