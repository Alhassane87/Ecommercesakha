<?php
// Créer toutes les tables nécessaires dans MySQL pour Sakha

$host = '127.0.0.1';
$port = '3306';
$database = 'sakha';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Désactiver les contraintes de clés étrangères
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    echo "✅ Connexion MySQL établie\n";
    
    // Table products
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description TEXT NULL,
        price DECIMAL(10, 2) NOT NULL,
        stock INT DEFAULT 0,
        category_id BIGINT UNSIGNED NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_category_id (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table products créée\n";
    
    // Table product_images
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_images (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id BIGINT UNSIGNED NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        is_primary TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table product_images créée\n";
    
    // Table product_variations
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_variations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id BIGINT UNSIGNED NOT NULL,
        sku VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NULL,
        stock INT DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table product_variations créée\n";
    
    // Table category_attributes
    $pdo->exec("CREATE TABLE IF NOT EXISTS category_attributes (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(255) NOT NULL,
        type VARCHAR(50) DEFAULT 'select',
        is_required TINYINT(1) DEFAULT 0,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_category_id (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table category_attributes créée\n";
    
    // Table category_attribute_values
    $pdo->exec("CREATE TABLE IF NOT EXISTS category_attribute_values (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_attribute_id BIGINT UNSIGNED NOT NULL,
        value VARCHAR(255) NOT NULL,
        display_value VARCHAR(255) NULL,
        color_code VARCHAR(255) NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_category_attribute_id (category_attribute_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table category_attribute_values créée\n";
    
    // Table carts
    $pdo->exec("CREATE TABLE IF NOT EXISTS carts (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL UNIQUE,
        session_id VARCHAR(255) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table carts créée\n";
    
    // Table cart_items
    $pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        cart_id BIGINT UNSIGNED NOT NULL,
        product_id BIGINT UNSIGNED NOT NULL,
        quantity INT DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_cart_id (cart_id),
        INDEX idx_product_id (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table cart_items créée\n";
    
    // Table orders
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        total DECIMAL(10, 2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table orders créée\n";
    
    // Table order_items
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        product_id BIGINT UNSIGNED NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_order_id (order_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table order_items créée\n";
    
    // Table payments
    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_order_id (order_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table payments créée\n";
    
    // Table promotions
    $pdo->exec("CREATE TABLE IF NOT EXISTS promotions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(255) NOT NULL UNIQUE,
        discount_type VARCHAR(50) NOT NULL,
        discount_value DECIMAL(10, 2) NOT NULL,
        start_date TIMESTAMP NULL,
        end_date TIMESTAMP NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table promotions créée\n";
    
    // Table conversations
    $pdo->exec("CREATE TABLE IF NOT EXISTS conversations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        title VARCHAR(255) NULL,
        status VARCHAR(50) DEFAULT 'active',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table conversations créée\n";
    
    // Table messages
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        conversation_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        content TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_conversation_id (conversation_id),
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table messages créée\n";
    
    // Table whatsapp_sessions
    $pdo->exec("CREATE TABLE IF NOT EXISTS whatsapp_sessions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        session_data TEXT NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table whatsapp_sessions créée\n";
    
    // Réactiver les contraintes
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Insérer des produits de test
    $pdo->exec("INSERT IGNORE INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES
        ('Smartphone Galaxy S24', 'smartphone-galaxy-s24', 'Dernier smartphone Samsung', 450000, 15, 1, 1, NOW(), NOW()),
        ('Laptop Pro 15', 'laptop-pro-15', 'Ordinateur portable', 650000, 8, 1, 1, NOW(), NOW()),
        ('T-shirt Premium', 'tshirt-premium', 'Coton bio', 15000, 50, 2, 1, NOW(), NOW()),
        ('Baskets Running', 'baskets-running', 'Chaussures sport', 85000, 25, 2, 1, NOW(), NOW()),
        ('Canapé 3 Places', 'canape-3-places', 'Convertible', 320000, 5, 3, 1, NOW(), NOW())");
    
    echo "✅ Produits de test insérés\n";
    
    echo "\n🎉 TOUTES LES TABLES ONT ÉTÉ CRÉÉES AVEC SUCCÈS !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
