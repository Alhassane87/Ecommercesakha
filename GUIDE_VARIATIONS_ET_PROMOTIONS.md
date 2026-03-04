# Guide : Variations de Produits et Promotions

## 📋 Vue d'ensemble

Ce système permet de :
1. **Définir des attributs par catégorie** (ex: T-shirts → Taille, Couleur)
2. **Créer des variations de produits** avec prix et stock spécifiques
3. **Gérer des promotions/réductions** (pourcentage ou montant fixe)

---

## 🚀 Installation

### 1. Lancer les migrations

```powershell
php artisan migrate
```

Cela créera les tables suivantes :
- `category_attributes` - Attributs définis par catégorie
- `category_attribute_values` - Valeurs possibles pour chaque attribut
- `product_variations` - Variations de produits (combinaisons taille/couleur)
- `promotions` - Promotions et réductions
- Mise à jour de `cart_items` pour stocker les variations
- Mise à jour de `products` pour les réductions

---

## 📝 Utilisation

### Étape 1 : Définir les attributs pour une catégorie

**Exemple : Pour la catégorie "T-shirts"**

1. Aller dans l'admin → Catégories
2. Éditer la catégorie "T-shirts"
3. Ajouter les attributs :
   - **Taille** : S, M, L, XL, XXL
   - **Couleur** : Rouge, Bleu, Noir, Blanc

**Via Tinker (pour tester rapidement) :**

```php
php artisan tinker

// Créer la catégorie T-shirts si elle n'existe pas
$category = \App\Models\Category::firstOrCreate(['name' => 'T-shirts', 'slug' => 't-shirts']);

// Créer l'attribut "Taille"
$tailleAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Taille',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 1
]);

// Ajouter les valeurs pour Taille
$tailles = ['S', 'M', 'L', 'XL', 'XXL'];
foreach ($tailles as $index => $taille) {
    \App\Models\CategoryAttributeValue::create([
        'category_attribute_id' => $tailleAttr->id,
        'value' => $taille,
        'display_value' => $taille,
        'sort_order' => $index
    ]);
}

// Créer l'attribut "Couleur"
$couleurAttr = \App\Models\CategoryAttribute::create([
    'category_id' => $category->id,
    'name' => 'Couleur',
    'type' => 'select',
    'is_required' => true,
    'sort_order' => 2
]);

// Ajouter les valeurs pour Couleur
$couleurs = [
    ['value' => 'Rouge', 'color_code' => '#FF0000'],
    ['value' => 'Bleu', 'color_code' => '#0000FF'],
    ['value' => 'Noir', 'color_code' => '#000000'],
    ['value' => 'Blanc', 'color_code' => '#FFFFFF']
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

### Étape 2 : Créer un produit avec variations

**Via Tinker :**

```php
// Créer le produit
$product = \App\Models\Product::create([
    'category_id' => $category->id,
    'name' => 'T-shirt Premium',
    'slug' => 't-shirt-premium',
    'price' => 5000, // Prix de base
    'stock' => 0, // Stock géré par variations
    'description' => 'Un super t-shirt'
]);

// Créer des variations
$variations = [
    ['taille' => 'S', 'couleur' => 'Rouge', 'stock' => 10, 'price' => 5000],
    ['taille' => 'M', 'couleur' => 'Rouge', 'stock' => 15, 'price' => 5000],
    ['taille' => 'L', 'couleur' => 'Rouge', 'stock' => 8, 'price' => 5000],
    ['taille' => 'S', 'couleur' => 'Bleu', 'stock' => 12, 'price' => 5000],
    ['taille' => 'M', 'couleur' => 'Bleu', 'stock' => 20, 'price' => 5000],
];

foreach ($variations as $var) {
    \App\Models\ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'TSH-' . strtoupper($var['taille']) . '-' . strtoupper(substr($var['couleur'], 0, 3)),
        'attributes' => ['taille' => $var['taille'], 'couleur' => $var['couleur']],
        'price' => $var['price'],
        'stock' => $var['stock'],
        'is_active' => true
    ]);
}
```

### Étape 3 : Créer une promotion

**Exemple : Réduction de 30% sur tous les T-shirts**

```php
\App\Models\Promotion::create([
    'name' => 'Soldes T-shirts -30%',
    'description' => 'Réduction de 30% sur tous les t-shirts',
    'type' => 'percentage',
    'value' => 30,
    'category_id' => $category->id, // Promotion sur la catégorie T-shirts
    'starts_at' => now(),
    'ends_at' => now()->addDays(30),
    'is_active' => true
]);
```

**Exemple : Réduction de 5000 fcfa sur un produit spécifique**

```php
\App\Models\Promotion::create([
    'name' => 'Promo spéciale',
    'type' => 'fixed',
    'value' => 5000,
    'product_id' => $product->id,
    'starts_at' => now(),
    'ends_at' => now()->addDays(7),
    'is_active' => true
]);
```

---

## 🎯 Fonctionnalités

### Pour les Produits

- `$product->hasVariants()` - Vérifie si le produit a des variations
- `$product->variations` - Liste des variations
- `$product->getFinalPrice($quantity)` - Prix final avec promotion appliquée
- `$product->getDiscountPercentage()` - Pourcentage de réduction

### Pour les Catégories

- `$category->attributes` - Liste des attributs (Taille, Couleur, etc.)
- `$category->activePromotions()` - Promotions actives sur la catégorie

### Pour les Variations

- `$variation->getFinalPriceAttribute()` - Prix de la variation (ou prix du produit)
- `$variation->inStock()` - Vérifie le stock
- `$variation->attributes` - Attributs sélectionnés (JSON)

---

## 🔄 Prochaines étapes

Les contrôleurs et vues doivent être mis à jour pour :
1. Gérer les attributs dans l'admin (création/édition)
2. Afficher les options sur la page produit
3. Gérer les variations dans le panier
4. Afficher les promotions actives

**Souhaitez-vous que je continue avec la mise à jour des contrôleurs et vues ?**

