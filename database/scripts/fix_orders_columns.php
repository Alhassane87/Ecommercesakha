<?php
// Ajouter les colonnes manquantes à la table orders

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier et ajouter customer_email
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'customer_email'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN customer_email VARCHAR(255) NULL AFTER total");
        echo "✅ Colonne customer_email ajoutée\n";
    } else {
        echo "ℹ️ Colonne customer_email existe déjà\n";
    }
    
    // Vérifier et ajouter customer_phone
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'customer_phone'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN customer_phone VARCHAR(255) NULL AFTER customer_email");
        echo "✅ Colonne customer_phone ajoutée\n";
    } else {
        echo "ℹ️ Colonne customer_phone existe déjà\n";
    }
    
    // Vérifier et ajouter order_number
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'order_number'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN order_number VARCHAR(255) NULL AFTER customer_phone");
        echo "✅ Colonne order_number ajoutée\n";
    } else {
        echo "ℹ️ Colonne order_number existe déjà\n";
    }
    
    // Vérifier et ajouter tracking_number
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'tracking_number'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(255) NULL AFTER order_number");
        echo "✅ Colonne tracking_number ajoutée\n";
    } else {
        echo "ℹ️ Colonne tracking_number existe déjà\n";
    }
    
    // Vérifier et ajouter shipping_address (JSON)
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'shipping_address'");
    if ($result->rowCount() == 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN shipping_address JSON NULL AFTER tracking_number");
        echo "✅ Colonne shipping_address ajoutée\n";
    } else {
        echo "ℹ️ Colonne shipping_address existe déjà\n";
    }
    
    echo "\n🎉 Toutes les colonnes orders sont maintenant correctes !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
