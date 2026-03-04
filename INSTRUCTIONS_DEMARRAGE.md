# 🚀 Instructions de Démarrage - Système d'Attributs

## 📋 Étapes pour configurer le système

### 1. Lancer les migrations

```powershell
php artisan migrate
```

Cela créera toutes les tables nécessaires :
- `category_attributes` - Attributs par catégorie
- `category_attribute_values` - Valeurs des attributs
- `product_variations` - Variations de produits
- `promotions` - Promotions et réductions

### 2. Créer les attributs de base (Optionnel mais recommandé)

```powershell
php artisan db:seed --class=AttributeSeeder
```

Cela créera automatiquement :
- ✅ **Baskets** → Pointure (36-46) + Couleur
- ✅ **Téléphones** → Modèle + Capacité + Couleur
- ✅ **T-shirts** → Taille (S-XXL) + Couleur

### 3. Créer vos catégories

Si vous n'avez pas encore de catégories, créez-les via l'admin ou Tinker :

```php
php artisan tinker

\App\Models\Category::create([
    'name' => 'Baskets',
    'slug' => 'baskets',
    'is_active' => true
]);
```

### 4. Créer des produits avec variations

#### Exemple : Baskets Nike

```php
php artisan tinker

$category = \App\Models\Category::where('name', 'Baskets')->first();
$product = \App\Models\Product::create([
    'category_id' => $category->id,
    'name' => 'Baskets Nike Air Max',
    'slug' => 'baskets-nike-air-max',
    'price' => 15000,
    'stock' => 0, // Stock géré par variations
    'description' => 'Baskets confortables',
    'is_active' => true
]);

// Créer des variations
$pointures = ['40', '41', '42', '43'];
$couleurs = ['Noir', 'Blanc', 'Rouge'];

foreach ($pointures as $pointure) {
    foreach ($couleurs as $couleur) {
        \App\Models\ProductVariation::create([
            'product_id' => $product->id,
            'sku' => 'NIKE-AM-' . $pointure . '-' . strtoupper(substr($couleur, 0, 3)),
            'attributes' => ['pointure' => $pointure, 'couleur' => $couleur],
            'price' => 15000,
            'stock' => rand(5, 20),
            'is_active' => true
        ]);
    }
}
```

#### Exemple : Téléphone iPhone

```php
$category = \App\Models\Category::where('name', 'Téléphones')->first();
$product = \App\Models\Product::create([
    'category_id' => $category->id,
    'name' => 'iPhone 15',
    'slug' => 'iphone-15',
    'price' => 800000,
    'stock' => 0,
    'description' => 'Le dernier iPhone',
    'is_active' => true
]);

// Variations avec prix différents selon la capacité
$variations = [
    ['modele' => 'iPhone 15', 'capacite' => '128GB', 'couleur' => 'Noir', 'price' => 800000, 'stock' => 10],
    ['modele' => 'iPhone 15', 'capacite' => '256GB', 'couleur' => 'Noir', 'price' => 900000, 'stock' => 8],
    ['modele' => 'iPhone 15', 'capacite' => '512GB', 'couleur' => 'Noir', 'price' => 1100000, 'stock' => 5],
];

foreach ($variations as $var) {
    \App\Models\ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'IPH15-' . $var['capacite'] . '-' . strtoupper(substr($var['couleur'], 0, 3)),
        'attributes' => [
            'modèle' => $var['modele'],
            'capacité' => $var['capacite'],
            'couleur' => $var['couleur']
        ],
        'price' => $var['price'],
        'stock' => $var['stock'],
        'is_active' => true
    ]);
}
```

### 5. Créer une promotion (Optionnel)

```php
$category = \App\Models\Category::where('name', 'Baskets')->first();
\App\Models\Promotion::create([
    'name' => 'Soldes Baskets -30%',
    'description' => 'Réduction de 30% sur tous les baskets',
    'type' => 'percentage',
    'value' => 30,
    'category_id' => $category->id,
    'starts_at' => now(),
    'ends_at' => now()->addDays(30),
    'is_active' => true
]);
```

---

## ✅ Vérification

1. Accédez à votre site : `http://127.0.0.1:8000`
2. Allez sur un produit qui a des variations
3. Vous devriez voir les options (Pointure, Couleur, etc.)
4. Sélectionnez les options et ajoutez au panier

---

## 📚 Documentation

- `GUIDE_ATTRIBUTS_PAR_CATEGORIE.md` - Exemples détaillés pour chaque type de catégorie
- `GUIDE_VARIATIONS_ET_PROMOTIONS.md` - Guide général du système
- `RESUME_MISE_A_JOUR.md` - Résumé technique

---

## 🎯 Types d'attributs supportés

Le système supporte **n'importe quel type d'attribut** selon la catégorie :

- ✅ **Pointure** (Baskets) - 36, 37, 38, etc.
- ✅ **Taille** (Vêtements) - S, M, L, XL, XXL
- ✅ **Couleur** (Tous) - Avec codes couleur visuels
- ✅ **Modèle** (Téléphones) - iPhone 15, Samsung S24, etc.
- ✅ **Capacité** (Téléphones) - 64GB, 128GB, 256GB, etc.
- ✅ **Tout autre attribut personnalisé** selon vos besoins !

**Chaque catégorie peut avoir ses propres attributs spécifiques !**

