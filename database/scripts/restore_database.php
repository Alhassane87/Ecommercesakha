<?php
// Script pour restaurer les données perdues

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Product;

try {
    // Créer utilisateurs
    User::create([
        'name' => 'Admin',
        'email' => 'admin@sakha.com',
        'password' => bcrypt('password'),
    ]);
    
    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    
    echo "✅ 2 utilisateurs créés\n";
    
    // Créer catégories
    $cats = [
        ['name' => 'Électronique', 'slug' => 'electronique', 'description' => 'Produits électroniques'],
        ['name' => 'Mode', 'slug' => 'mode', 'description' => 'Vêtements et accessoires'],
        ['name' => 'Maison', 'slug' => 'maison', 'description' => 'Meubles et décoration'],
        ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Équipements sportifs'],
        ['name' => 'Beauté', 'slug' => 'beaute', 'description' => 'Produits de beauté'],
    ];
    
    foreach ($cats as $cat) {
        Category::create(array_merge($cat, ['is_active' => true]));
    }
    
    echo "✅ 5 catégories créées\n";
    
    // Créer produits
    $prods = [
        ['name' => 'Smartphone Galaxy S24', 'slug' => 'smartphone-galaxy-s24', 'description' => 'Smartphone Samsung 200MP', 'price' => 450000, 'stock' => 15, 'category_id' => 1, 'is_active' => true],
        ['name' => 'Laptop Pro 15', 'slug' => 'laptop-pro-15', 'description' => 'Ordinateur portable pro', 'price' => 650000, 'stock' => 8, 'category_id' => 1, 'is_active' => true],
        ['name' => 'T-shirt Premium', 'slug' => 'tshirt-premium', 'description' => 'T-shirt coton bio', 'price' => 15000, 'stock' => 50, 'category_id' => 2, 'is_active' => true],
        ['name' => 'Canapé 3 Places', 'slug' => 'canape-3-places', 'description' => 'Canapé convertible', 'price' => 320000, 'stock' => 5, 'category_id' => 3, 'is_active' => true],
    ];
    
    foreach ($prods as $prod) {
        Product::create($prod);
    }
    
    echo "✅ 4 produits créés\n";
    echo "\n🎉 Données restaurées !\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
