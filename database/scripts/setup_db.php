<?php
// Simple test to verify MySQL connection and create tables

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8mb4", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sakha CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'sakha' created or exists\n";
    
    // Select the database
    $pdo->exec("USE sakha");
    
    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Drop existing tables
    $tables = ['cart_items', 'carts', 'product_images', 'products', 'categories', 'orders', 'order_items', 'sessions', 'password_reset_tokens', 'users', 'migrations', 'cache', 'cache_locks', 'jobs'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
    }
    echo "✅ Existing tables dropped\n";
    
    // Create tables
    $pdo->exec("CREATE TABLE migrations (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, batch INT NOT NULL)");
    
    $pdo->exec("CREATE TABLE users (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, email_verified_at TIMESTAMP NULL, password VARCHAR(255) NOT NULL, google_id VARCHAR(255) NULL, google_token VARCHAR(255) NULL, google_refresh_token VARCHAR(255) NULL, role VARCHAR(50) DEFAULT 'user', remember_token VARCHAR(100) NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
    
    $pdo->exec("CREATE TABLE password_reset_tokens (email VARCHAR(255) PRIMARY KEY, token VARCHAR(255) NOT NULL, created_at TIMESTAMP NULL)");
    
    $pdo->exec("CREATE TABLE sessions (id VARCHAR(128) NOT NULL PRIMARY KEY, user_id BIGINT UNSIGNED NULL, ip_address VARCHAR(45) NULL, user_agent TEXT NULL, payload LONGTEXT NOT NULL, last_activity INT NOT NULL, INDEX(user_id), INDEX(last_activity))");
    
    $pdo->exec("CREATE TABLE categories (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, description TEXT NULL, is_active TINYINT(1) DEFAULT 1, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL)");
    
    $pdo->exec("CREATE TABLE products (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, description TEXT NULL, price DECIMAL(10,2) NOT NULL, stock INT DEFAULT 0, category_id BIGINT UNSIGNED NULL, is_active TINYINT(1) DEFAULT 1, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(category_id))");
    
    $pdo->exec("CREATE TABLE product_images (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, product_id BIGINT UNSIGNED NOT NULL, image_path VARCHAR(255) NOT NULL, is_primary TINYINT(1) DEFAULT 0, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(product_id))");
    
    $pdo->exec("CREATE TABLE carts (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id BIGINT UNSIGNED NULL UNIQUE, session_id VARCHAR(255) NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(user_id))");
    
    $pdo->exec("CREATE TABLE cart_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, cart_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT DEFAULT 1, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(cart_id), INDEX(product_id))");
    
    $pdo->exec("CREATE TABLE orders (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id BIGINT UNSIGNED NOT NULL, total DECIMAL(10,2) NOT NULL, status VARCHAR(50) DEFAULT 'pending', created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(user_id))");
    
    $pdo->exec("CREATE TABLE order_items (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, order_id BIGINT UNSIGNED NOT NULL, product_id BIGINT UNSIGNED NOT NULL, quantity INT NOT NULL, price DECIMAL(10,2) NOT NULL, created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL, INDEX(order_id))");
    
    $pdo->exec("CREATE TABLE cache (key VARCHAR(255) NOT NULL PRIMARY KEY, value MEDIUMTEXT NOT NULL, expiration INT NOT NULL)");
    
    $pdo->exec("CREATE TABLE cache_locks (key VARCHAR(255) NOT NULL PRIMARY KEY, owner VARCHAR(255) NOT NULL, expiration INT NOT NULL)");
    
    $pdo->exec("CREATE TABLE jobs (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, queue VARCHAR(255) NOT NULL, payload LONGTEXT NOT NULL, attempts TINYINT UNSIGNED NOT NULL, reserved_at INT UNSIGNED NULL, available_at INT UNSIGNED NOT NULL, created_at INT UNSIGNED NOT NULL, INDEX(queue))");
    
    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "✅ All tables created successfully\n";
    
    // Insert sample data
    $pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES 
        ('Électronique', 'electronique', 'Produits électroniques', 1, NOW(), NOW()),
        ('Mode', 'mode', 'Vêtements', 1, NOW(), NOW()),
        ('Maison', 'maison', 'Meubles', 1, NOW(), NOW()),
        ('Sports', 'sports', 'Équipements', 1, NOW(), NOW()),
        ('Beauté', 'beaute', 'Produits', 1, NOW(), NOW())");
    
    $pdo->exec("INSERT INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES
        ('Smartphone S24', 'smartphone-s24', 'Samsung', 450000, 15, 1, 1, NOW(), NOW()),
        ('Laptop Pro', 'laptop-pro', 'PC', 650000, 8, 1, 1, NOW(), NOW()),
        ('T-shirt', 't-shirt', 'Coton', 15000, 50, 2, 1, NOW(), NOW())");
    
    echo "✅ Sample data inserted\n";
    echo "\n🎉 Database setup complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
