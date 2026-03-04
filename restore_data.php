<?php
require __DIR__ . '/vendor/autoload.php';

$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Users
    $pass = password_hash('password', PASSWORD_DEFAULT);
    $pdo->exec("DELETE FROM users WHERE email IN ('admin@sakha.com', 'test@example.com')");
    $pdo->exec("INSERT INTO users (name, email, password, created_at, updated_at) VALUES 
        ('Admin', 'admin@sakha.com', '$pass', datetime('now'), datetime('now')),
        ('Test User', 'test@example.com', '$pass', datetime('now'), datetime('now'))");
    
    echo "✅ Utilisateurs créés\n";
    
    // Categories
    $pdo->exec("DELETE FROM categories");
    $pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES 
        ('Électronique', 'electronique', 'Produits électroniques', 1, datetime('now'), datetime('now')),
        ('Mode', 'mode', 'Vêtements', 1, datetime('now'), datetime('now')),
        ('Maison', 'maison', 'Meubles', 1, datetime('now'), datetime('now')),
        ('Sports', 'sports', 'Équipements', 1, datetime('now'), datetime('now')),
        ('Beauté', 'beaute', 'Produits beauté', 1, datetime('now'), datetime('now'))");
    
    echo "✅ Catégories créées\n";
    
    // Products
    $pdo->exec("DELETE FROM products");
    $pdo->exec("INSERT INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES 
        ('Smartphone S24', 'smartphone-s24', 'Samsung 200MP', 450000, 15, 1, 1, datetime('now'), datetime('now')),
        ('Laptop Pro', 'laptop-pro', 'PC portable', 650000, 8, 1, 1, datetime('now'), datetime('now')),
        ('T-shirt', 't-shirt', 'Coton bio', 15000, 50, 2, 1, datetime('now'), datetime('now')),
        ('Canapé', 'canape', '3 places', 320000, 5, 3, 1, datetime('now'), datetime('now'))");
    
    echo "✅ Produits créés\n";
    echo "\n🎉 Données restaurées !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
