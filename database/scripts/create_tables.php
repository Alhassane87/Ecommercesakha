<?php
// Script pour créer toutes les tables manquantes directement

$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Créer la table orders
$pdo->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    order_number VARCHAR(255) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(255) DEFAULT 'pending',
    payment_status VARCHAR(255) DEFAULT 'pending',
    payment_method VARCHAR(255) NULL,
    shipping_address TEXT NULL,
    billing_address TEXT NULL,
    phone VARCHAR(255) NULL,
    notes TEXT NULL,
    paid_at DATETIME NULL,
    shipped_at DATETIME NULL,
    delivered_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Créer la table cart_items
$pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NULL,
    product_id INTEGER NOT NULL,
    product_variation_id INTEGER NULL,
    quantity INTEGER DEFAULT 1,
    selected_attributes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (product_variation_id) REFERENCES product_variations(id) ON DELETE SET NULL
)");

echo "✅ Tables créées avec succès !\n";
echo "- orders\n";
echo "- cart_items\n";
