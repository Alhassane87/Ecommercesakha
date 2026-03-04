<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\CategoryAttributeValue;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        // BASKETS / CHAUSSURES
        $baskets = Category::firstOrCreate(['name' => 'Baskets', 'slug' => 'baskets']);
        $this->createPointureAttribute($baskets);
        $this->createCouleurAttribute($baskets, 2);

        // TÉLÉPHONES / SMARTPHONES
        $telephones = Category::firstOrCreate(['name' => 'Téléphones', 'slug' => 'telephones']);
        $this->createModeleAttribute($telephones);
        $this->createCapaciteAttribute($telephones);
        $this->createCouleurAttribute($telephones, 3);

        // T-SHIRTS / VÊTEMENTS
        $tshirts = Category::firstOrCreate(['name' => 'T-shirts', 'slug' => 't-shirts']);
        $this->createTailleAttribute($tshirts);
        $this->createCouleurAttribute($tshirts, 2);
    }

    private function createPointureAttribute($category)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Pointure',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => 1
        ]);

        $pointures = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'];
        foreach ($pointures as $index => $pointure) {
            CategoryAttributeValue::create([
                'category_attribute_id' => $attr->id,
                'value' => $pointure,
                'display_value' => "Pointure {$pointure}",
                'sort_order' => $index
            ]);
        }
    }

    private function createTailleAttribute($category)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Taille',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => 1
        ]);

        $tailles = ['S', 'M', 'L', 'XL', 'XXL'];
        foreach ($tailles as $index => $taille) {
            CategoryAttributeValue::create([
                'category_attribute_id' => $attr->id,
                'value' => $taille,
                'display_value' => $taille,
                'sort_order' => $index
            ]);
        }
    }

    private function createCouleurAttribute($category, $sortOrder = 2)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Couleur',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => $sortOrder
        ]);

        $couleurs = [
            ['value' => 'Noir', 'color_code' => '#000000'],
            ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
            ['value' => 'Rouge', 'color_code' => '#FF0000'],
            ['value' => 'Bleu', 'color_code' => '#0000FF'],
            ['value' => 'Gris', 'color_code' => '#808080'],
            ['value' => 'Vert', 'color_code' => '#00FF00'],
        ];

        foreach ($couleurs as $index => $couleur) {
            CategoryAttributeValue::create([
                'category_attribute_id' => $attr->id,
                'value' => $couleur['value'],
                'display_value' => $couleur['value'],
                'color_code' => $couleur['color_code'],
                'sort_order' => $index
            ]);
        }
    }

    private function createModeleAttribute($category)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Modèle',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => 1
        ]);

        $modeles = [
            'iPhone 15',
            'iPhone 15 Pro',
            'iPhone 15 Pro Max',
            'Samsung Galaxy S24',
            'Samsung Galaxy S24 Ultra',
            'Xiaomi Redmi Note 13',
        ];

        foreach ($modeles as $index => $modele) {
            CategoryAttributeValue::create([
                'category_attribute_id' => $attr->id,
                'value' => $modele,
                'display_value' => $modele,
                'sort_order' => $index
            ]);
        }
    }

    private function createCapaciteAttribute($category)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Capacité',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => 2
        ]);

        $capacites = ['64GB', '128GB', '256GB', '512GB', '1TB'];
        foreach ($capacites as $index => $capacite) {
            CategoryAttributeValue::create([
                'category_attribute_id' => $attr->id,
                'value' => $capacite,
                'display_value' => $capacite,
                'sort_order' => $index
            ]);
        }
    }
}

