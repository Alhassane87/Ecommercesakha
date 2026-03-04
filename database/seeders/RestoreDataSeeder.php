<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestoreDataSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin',
                'email' => 'admin@sakha.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Categories
        DB::table('categories')->insertOrIgnore([
            ['name' => 'Électronique', 'slug' => 'electronique', 'description' => 'Produits électroniques', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mode', 'slug' => 'mode', 'description' => 'Vêtements et accessoires', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maison', 'slug' => 'maison', 'description' => 'Meubles et décoration', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Équipements sportifs', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beauté', 'slug' => 'beaute', 'description' => 'Produits de beauté', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Products
        DB::table('products')->insertOrIgnore([
            ['name' => 'Smartphone Galaxy S24', 'slug' => 'smartphone-galaxy-s24', 'description' => 'Dernier smartphone Samsung', 'price' => 450000, 'stock' => 15, 'category_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptop Pro 15', 'slug' => 'laptop-pro-15', 'description' => 'Ordinateur portable pro', 'price' => 650000, 'stock' => 8, 'category_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'T-shirt Premium', 'slug' => 'tshirt-premium', 'description' => 'T-shirt coton bio', 'price' => 15000, 'stock' => 50, 'category_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Canapé 3 Places', 'slug' => 'canape-3-places', 'description' => 'Canapé convertible', 'price' => 320000, 'stock' => 5, 'category_id' => 3, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('✅ Données restaurées avec succès !');
    }
}
