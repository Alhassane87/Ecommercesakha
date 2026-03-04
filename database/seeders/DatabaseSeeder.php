<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sakha.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Électronique', 'slug' => 'electronique', 'description' => 'Produits électroniques et high-tech', 'is_active' => true],
            ['name' => 'Mode', 'slug' => 'mode', 'description' => 'Vêtements, chaussures et accessoires', 'is_active' => true],
            ['name' => 'Maison', 'slug' => 'maison', 'description' => 'Meubles et décoration', 'is_active' => true],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Équipements sportifs', 'is_active' => true],
            ['name' => 'Beauté', 'slug' => 'beaute', 'description' => 'Produits de beauté et soins', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create sample products
        $products = [
            ['name' => 'Smartphone Galaxy S24', 'slug' => 'smartphone-galaxy-s24', 'description' => 'Dernier smartphone Samsung avec appareil photo 200MP', 'price' => 450000, 'stock' => 15, 'category_id' => 1, 'is_active' => true],
            ['name' => 'Laptop Pro 15"', 'slug' => 'laptop-pro-15', 'description' => 'Ordinateur portable professionnel haute performance', 'price' => 650000, 'stock' => 8, 'category_id' => 1, 'is_active' => true],
            ['name' => 'T-shirt Premium Coton', 'slug' => 'tshirt-premium-coton', 'description' => 'T-shirt 100% coton bio', 'price' => 15000, 'stock' => 50, 'category_id' => 2, 'is_active' => true],
            ['name' => 'Baskets Running Air', 'slug' => 'baskets-running-air', 'description' => 'Chaussures de running légères', 'price' => 85000, 'stock' => 25, 'category_id' => 2, 'is_active' => true],
            ['name' => 'Canapé Convertible 3 Places', 'slug' => 'canape-convertible-3-places', 'description' => 'Canapé moderne convertible', 'price' => 320000, 'stock' => 5, 'category_id' => 3, 'is_active' => true],
            ['name' => 'Lampe Design LED', 'slug' => 'lampe-design-led', 'description' => 'Lampe de table moderne LED', 'price' => 45000, 'stock' => 30, 'category_id' => 3, 'is_active' => true],
            ['name' => 'Set Haltères 20kg', 'slug' => 'set-halteres-20kg', 'description' => 'Set d haltères réglables', 'price' => 75000, 'stock' => 12, 'category_id' => 4, 'is_active' => true],
            ['name' => 'Tapis de Yoga Écologique', 'slug' => 'tapis-yoga-ecologique', 'description' => 'Tapis de yoga antidérapant', 'price' => 25000, 'stock' => 40, 'category_id' => 4, 'is_active' => true],
            ['name' => 'Crème Hydratante Visage', 'slug' => 'creme-hydratante-visage', 'description' => 'Crème hydratante tous types de peau', 'price' => 18000, 'stock' => 60, 'category_id' => 5, 'is_active' => true],
            ['name' => 'Parfum Élégance 100ml', 'slug' => 'parfum-elegance-100ml', 'description' => 'Parfum floral jasmin et vanille', 'price' => 65000, 'stock' => 20, 'category_id' => 5, 'is_active' => true],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }
    }
}
