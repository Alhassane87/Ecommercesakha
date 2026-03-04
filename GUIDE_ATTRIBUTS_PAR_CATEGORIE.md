# 📦 Guide : Attributs par Catégorie - Exemples Concrets

## 🎯 Vue d'ensemble

Ce guide montre comment configurer les attributs pour différentes catégories de produits e-commerce.

---

## 👟 1. BASKETS / CHAUSSURES

### Attributs à créer :
- **Pointure** (obligatoire)
- **Couleur** (obligatoire)

### Configuration via Tinker :

```php
php artisan tinker

// Trouver ou créer la catégorie Baskets
$category = \App\Models\Category::firstOrCreate(
    ['name' => 'Baskets', 'slug' => 'baskets']
);

// 1. Créer l'attribut POINTURE
$pointureAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Pointure',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 1
]);

// Ajouter les pointures (36 à 46)
$pointures = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'];
foreach ($pointures as $index => $pointure) {
    \App\Models\CategoryAttributeValue::create([
        'category_attribute_id' => $pointureAttr->id,
        'value' => $pointure,
        'display_value' => "Pointure {$pointure}",
        'sort_order' => $index
    ]);
}

// 2. Créer l'attribut COULEUR
$couleurAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Couleur',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 2
]);

// Ajouter les couleurs
$couleurs = [
    ['value' => 'Noir', 'color_code' => '#000000'],
    ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
    ['value' => 'Rouge', 'color_code' => '#FF0000'],
    ['value' => 'Bleu', 'color_code' => '#0000FF'],
    ['value' => 'Gris', 'color_code' => '#808080'],
    ['value' => 'Vert', 'color_code' => '#00FF00'],
    ['value' => 'Jaune', 'color_code' => '#FFFF00'],
    ['value' => 'Orange', 'color_code' => '#FFA500'],
    ['value' => 'Rose', 'color_code' => '#FFC0CB'],
    ['value' => 'Marron', 'color_code' => '#800000'],
    ['value' => 'Violet', 'color_code' => '#8A2BE2'],
    ['value' => 'Cyan', 'color_code' => '#00FFFF'],
    ['value' => 'Fuchsia', 'color_code' => '#FF00FF'],
    ['value' => 'Turquoise', 'color_code' => '#40E0D0'],
    ['value' => 'Olive', 'color_code' => '#808000'],
    ['value' => 'Sienna', 'color_code' => '#A0522D'],
    ['value' => 'Coral', 'color_code' => '#FF7F50'],
    ['value' => 'Navy', 'color_code' => '#000080'],
    ['value' => 'Teal', 'color_code' => '#008080'],
    ['value' => 'Lavender', 'color_code' => '#E6E6FA'],
    ['value' => 'Salmon', 'color_code' => '#FA8072'],
    ['value' => 'Khaki', 'color_code' => '#F0E68C'],
    ['value' => 'Plum', 'color_code' => '#DDA0DD'],
    ['value' => 'Gold', 'color_code' => '#FFD700'],
    ['value' => 'Indigo', 'color_code' => '#4B0082'],
    ['value' => 'Lime', 'color_code' => '#00FF00'],
    ['value' => 'Silver', 'color_code' => '#C0C0C0'],
    ['value' => 'Fuchsia', 'color_code' => '#FF00FF'],
    ['value' => 'Crimson', 'color_code' => '#DC143C'],
    ['value' => 'Maroon', 'color_code' => '#800000'],
    ['value' => 'Navy', 'color_code' => '#000080'],
    ['value' => 'Olive', 'color_code' => '#808000'],
    ['value' => 'Purple', 'color_code' => '#800080'],
    ['value' => 'Teal', 'color_code' => '#008080'],
    ['value' => 'Yellow', 'color_code' => '#FFFF00'],
    ['value' => 'Aqua', 'color_code' => '#00FFFF'],
    ['value' => 'Fuchsia', 'color_code' => '#FF00FF'],
    ['value' => 'Coral', 'color_code' => '#FF7F50'],
    ['value' => 'Lavender', 'color_code' => '#E6E6FA'],
    ['value' => 'Salmon', 'color_code' => '#FA8072'],
];
foreach ($couleurs as $index => $couleur) {
    \App\Models\CategoryAttributeValue::create([
        'category_attribute_id' => $couleurAttr->id,
        'value' => $couleur['value'],
        'display_value' => $couleur['value'],
        'color_code' => $couleur['color_code'],
        'sort_order' => $index
    ]);
}
```

### Créer des variations de produit :

```php
$product = \App\Models\Product::create([
    'category_id' => $category->id,
    'name' => 'Baskets Nike Air Max',
    'slug' => 'baskets-nike-air-max',
    'price' => 15000,
    'stock' => 0, // Stock géré par variations
    'description' => 'Baskets confortables et stylées'
]);

// Créer des variations pour chaque combinaison pointure/couleur
$pointures = ['40', '41', '42', '43'];
$couleurs = ['Noir', 'Blanc', 'Rouge'];

foreach ($pointures as $pointure) {
    foreach ($couleurs as $couleur) {
        \App\Models\ProductVariation::create([
            'product_id' => $product->id,
            'sku' => 'NIKE-AM-' . $pointure . '-' . strtoupper(substr($couleur, 0, 3)),
            'attributes' => ['pointure' => $pointure, 'couleur' => $couleur],
            'price' => 15000,
            'stock' => rand(5, 20), // Stock aléatoire pour l'exemple
            'is_active' => true
        ]);
    }
}
```

---

## 📱 2. TÉLÉPHONES / SMARTPHONES

### Attributs à créer :
- **Modèle** (obligatoire) - Ex: iPhone 15, iPhone 15 Pro, Samsung Galaxy S24
- **Capacité** (obligatoire) - Ex: 64GB, 128GB, 256GB, 512GB
- **Couleur** (obligatoire)
- **Version** (optionnel) - Ex: Standard, Pro, Max, Ultra

### Configuration :

```php
$category = \App\Models\Category::firstOrCreate(
    ['name' => 'Téléphones', 'slug' => 'telephones']
);

// 1. Attribut MODÈLE
$modeleAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Modèle',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 1
]);

$modeles = ['iPhone 15', 'iPhone 15 Pro', 'iPhone 15 Pro Max', 'Samsung Galaxy S24', 'Samsung Galaxy S24 Ultra'];
foreach ($modeles as $index => $modele) {
    $modeleAttr->values()->create([
        'value' => $modele,
        'display_value' => $modele,
        'sort_order' => $index
    ]);
}

// 2. Attribut CAPACITÉ
$capaciteAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Capacité',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 2
]);

$capacites = ['64GB', '128GB', '256GB', '512GB', '1TB'];
foreach ($capacites as $index => $capacite) {
    $capaciteAttr->values()->create([
        'value' => $capacite,
        'display_value' => $capacite,
        'sort_order' => $index
    ]);
}

// 3. Attribut COULEUR
$couleurAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Couleur',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 3
]);

$couleurs = [
    ['value' => 'Noir', 'color_code' => '#000000'],
    ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
    ['value' => 'Bleu', 'color_code' => '#007AFF'],
    ['value' => 'Rose', 'color_code' => '#FF69B4'],
    ['value' => 'Violet', 'color_code' => '#8E44AD'],
];
foreach ($couleurs as $index => $couleur) {
    $couleurAttr->values()->create([
        'category_attribute_id' => $couleurAttr->id,
        'value' => $couleur['value'],
        'display_value' => $couleur['value'],
        'color_code' => $couleur['color_code'],
        'sort_order' => $index
    ]);
}
```

### Exemple de variations pour iPhone :

```php
$product = \App\Models\Product::create([
    'category_id' => $category->id,
    'name' => 'iPhone 15',
    'slug' => 'iphone-15',
    'price' => 800000, // Prix de base
    'stock' => 0,
    'description' => 'Le dernier iPhone'
]);

// Variations avec prix différents selon la capacité
$variations = [
    ['modele' => 'iPhone 15', 'capacite' => '128GB', 'couleur' => 'Noir', 'price' => 800000, 'stock' => 10],
    ['modele' => 'iPhone 15', 'capacite' => '256GB', 'couleur' => 'Noir', 'price' => 900000, 'stock' => 8],
    ['modele' => 'iPhone 15', 'capacite' => '512GB', 'couleur' => 'Noir', 'price' => 1100000, 'stock' => 5],
    ['modele' => 'iPhone 15', 'capacite' => '128GB', 'couleur' => 'Bleu', 'price' => 800000, 'stock' => 12],
    // ... etc
];

foreach ($variations as $var) {
    \App\Models\ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'IPH15-' . $var['capacite'] . '-' . strtoupper(substr($var['couleur'], 0, 3)),
        'attributes' => [
            'modele' => $var['modele'],
            'capacite' => $var['capacite'],
            'couleur' => $var['couleur']
        ],
        'price' => $var['price'], // Prix spécifique pour cette variation
        'stock' => $var['stock'],
        'is_active' => true
    ]);
}
```

---

## 👕 3. VÊTEMENTS / T-SHIRTS

### Attributs :
- **Taille** (S, M, L, XL, XXL)
- **Couleur**

### Configuration :

```php
$category = \App\Models\Category::firstOrCreate(
    ['name' => 'T-shirts', 'slug' => 't-shirts']
);

// Taille
$tailleAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Taille',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 1
]);

$tailles = ['S', 'M', 'L', 'XL', 'XXL'];
foreach ($tailles as $index => $taille) {
    $tailleAttr->values()->create([
        'value' => $taille,
        'display_value' => $taille,
        'sort_order' => $index
    ]);
}

// Couleur (comme pour les baskets)
```

---

## 💻 4. ORDINATEURS / LAPTOPS

### Attributs :
- **Processeur** (Intel i5, i7, AMD Ryzen 5, etc.)
- **RAM** (8GB, 16GB, 32GB)
- **Stockage** (256GB SSD, 512GB SSD, 1TB SSD)
- **Couleur** (optionnel)

---

## 📺 5. TÉLÉVISEURS

### Attributs :
- **Taille d'écran** (32", 43", 50", 55", 65", 75")
- **Résolution** (HD, Full HD, 4K, 8K)
- **Type** (LED, OLED, QLED)

---

## 🎧 6. ÉLECTRONIQUE (Casques, Écouteurs)

### Attributs :
- **Type de connexion** (Filaire, Bluetooth, USB-C)
- **Couleur**
- **Version** (Standard, Pro, Max)

---

## 🔧 Configuration Rapide - Script Complet

Créez un fichier `database/seeders/AttributeSeeder.php` :

```php
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
        // BASKETS
        $baskets = Category::firstOrCreate(['name' => 'Baskets', 'slug' => 'baskets']);
        $this->createPointureAttribute($baskets);
        $this->createCouleurAttribute($baskets);

        // TÉLÉPHONES
        $telephones = Category::firstOrCreate(['name' => 'Téléphones', 'slug' => 'telephones']);
        $this->createModeleAttribute($telephones);
        $this->createCapaciteAttribute($telephones);
        $this->createCouleurAttribute($telephones);

        // T-SHIRTS
        $tshirts = Category::firstOrCreate(['name' => 'T-shirts', 'slug' => 't-shirts']);
        $this->createTailleAttribute($tshirts);
        $this->createCouleurAttribute($tshirts);
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

    private function createCouleurAttribute($category)
    {
        $attr = CategoryAttribute::create([
            'category_id' => $category->id,
            'name' => 'Couleur',
            'type' => 'select',
            'is_required' => true,
            'sort_order' => 2
        ]);

        $couleurs = [
            ['value' => 'Noir', 'color_code' => '#000000'],
            ['value' => 'Blanc', 'color_code' => '#FFFFFF'],
            ['value' => 'Rouge', 'color_code' => '#FF0000'],
            ['value' => 'Bleu', 'color_code' => '#0000FF'],
            ['value' => 'Gris', 'color_code' => '#808080'],
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

        $modeles = ['iPhone 15', 'iPhone 15 Pro', 'Samsung Galaxy S24'];
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

        $capacites = ['64GB', '128GB', '256GB', '512GB'];
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
```

Puis lancer :
```powershell
php artisan db:seed --class=AttributeSeeder
```

---

## ✅ Résumé

Le système est **déjà flexible** et peut gérer n'importe quel type d'attribut selon la catégorie :

- ✅ **Baskets** → Pointure + Couleur
- ✅ **Téléphones** → Modèle + Capacité + Couleur
- ✅ **Vêtements** → Taille + Couleur
- ✅ **Tout autre produit** → Attributs personnalisés

**Chaque catégorie peut avoir ses propres attributs spécifiques !**

