<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Users
$pdo->exec("DELETE FROM users WHERE email IN ('admin@sakha.com', 'test@example.com')");
$pass = password_hash('password', PASSWORD_DEFAULT);
$pdo->exec("INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('Admin', 'admin@sakha.com', '$pass', datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO users (name, email, password, created_at, updated_at) VALUES ('Test User', 'test@example.com', '$pass', datetime('now'), datetime('now'))");

// Categories
$pdo->exec("DELETE FROM categories");
$pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES ('Electronique', 'electronique', 'Produits electroniques', 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES ('Mode', 'mode', 'Vetements', 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES ('Maison', 'maison', 'Meubles', 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES ('Sports', 'sports', 'Equipements', 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES ('Beaute', 'beaute', 'Produits beaute', 1, datetime('now'), datetime('now'))");

// Products
$pdo->exec("DELETE FROM products");
$pdo->exec("INSERT INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES ('Smartphone S24', 'smartphone-s24', 'Samsung 200MP', 450000, 15, 1, 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES ('Laptop Pro', 'laptop-pro', 'PC portable', 650000, 8, 1, 1, datetime('now'), datetime('now'))");
$pdo->exec("INSERT INTO products (name, slug, description, price, stock, category_id, is_active, created_at, updated_at) VALUES ('T-shirt', 't-shirt', 'Coton bio', 15000, 50, 2, 1, datetime('now'), datetime('now'))");

echo "✅ Donnees restaurees\n";
