<?php
// Créer directement la table categories dans MySQL

try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la table categories
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description TEXT NULL,
        is_active TINYINT(1) DEFAULT 1,
        parent_id BIGINT UNSIGNED NULL,
        icon VARCHAR(255) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        INDEX idx_parent_id (parent_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    echo "✅ Table categories créée avec succès\n";
    
    // Insérer des catégories de test
    $pdo->exec("INSERT IGNORE INTO categories (name, slug, description, is_active, created_at, updated_at) VALUES
        ('Électronique', 'electronique', 'Produits électroniques et high-tech', 1, NOW(), NOW()),
        ('Mode', 'mode', 'Vêtements, chaussures et accessoires', 1, NOW(), NOW()),
        ('Maison', 'maison', 'Meubles et décoration', 1, NOW(), NOW()),
        ('Sports', 'sports', 'Équipements sportifs', 1, NOW(), NOW()),
        ('Beauté', 'beaute', 'Produits de beauté et soins', 1, NOW(), NOW())");
    
    echo "✅ Catégories de test insérées\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
