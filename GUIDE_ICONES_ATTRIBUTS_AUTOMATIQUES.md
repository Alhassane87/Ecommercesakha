# 🎯 Guide : Attributs Automatiques selon l'Icône de Catégorie

## ✨ Fonctionnement Automatique

Quand vous créez une catégorie et choisissez une icône, les attributs sont **automatiquement créés** selon l'icône !

---

## 📋 Mapping Icône → Attributs

### 👕 Mode (`fas fa-tshirt`)
**Produits concernés :** Pantalon, Pull, T-shirt, Robe, etc.

**Attributs automatiques :**
- ✅ **Taille** : S, M, L, XL, XXL
- ✅ **Couleur** : Noir, Blanc, Rouge, Bleu, Gris (avec codes couleur)

**Exemple :**
- Créer catégorie "Mode" avec icône T-shirt → Attributs Taille + Couleur créés automatiquement
- Créer produit "Pantalon" dans catégorie "Mode" → Le client voit Taille et Couleur

---

### 👟 Baskets (`fas fa-shoe-prints`)
**Produits concernés :** Baskets, Chaussures, Sneakers

**Attributs automatiques :**
- ✅ **Pointure** : 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46
- ✅ **Couleur** : Noir, Blanc, Rouge, Bleu (avec codes couleur)

---

### 📱 Téléphones (`fas fa-mobile-alt`)
**Produits concernés :** Smartphones, Téléphones

**Attributs automatiques :**
- ✅ **Modèle** : Standard, Pro, Max, Ultra
- ✅ **Capacité** : 64GB, 128GB, 256GB, 512GB, 1TB
- ✅ **Couleur** : Noir, Blanc, Bleu, Rose (avec codes couleur)

---

### 💻 Informatique (`fas fa-laptop`)
**Produits concernés :** Ordinateurs, Laptops

**Attributs automatiques :**
- ✅ **Processeur** : Intel i5, Intel i7, AMD Ryzen 5, AMD Ryzen 7
- ✅ **RAM** : 8GB, 16GB, 32GB
- ✅ **Stockage** : 256GB SSD, 512GB SSD, 1TB SSD
- ✅ **Couleur** : Noir, Argent

---

### 🏋️ Sport (`fas fa-dumbbell`)
**Produits concernés :** Vêtements de sport, Équipements

**Attributs automatiques :**
- ✅ **Taille** : S, M, L, XL, XXL
- ✅ **Couleur** : Noir, Blanc, Rouge, Bleu

---

## 🚀 Utilisation

### 1. Créer une catégorie avec attributs automatiques

1. Aller dans **Admin → Catégories → Créer**
2. Choisir le nom : "Mode"
3. Choisir l'icône : **T-shirt** (`fas fa-tshirt`)
4. Cliquer sur **"Créer la catégorie"**
5. ✅ Les attributs **Taille** et **Couleur** sont créés automatiquement !

### 2. Créer un produit dans cette catégorie

1. Créer un produit "Pantalon" dans la catégorie "Mode"
2. Le produit hérite automatiquement des attributs de la catégorie
3. Sur la page produit, le client voit :
   - **Taille** : S, M, L, XL, XXL
   - **Couleur** : Cercles colorés

### 3. Créer des variations

Pour chaque combinaison (ex: Taille M + Couleur Rouge), créez une variation avec stock et prix.

---

## 💰 Affichage des Prix avec Réductions

Sur la page produit, le client voit :

```
┌─────────────────────────────────┐
│ Prix                             │
│ 15 000 FCFA  [barré]             │
│ 10 500 FCFA  [grand prix]        │
│ -30% [badge rouge]               │
│ Économisez 4 500 FCFA            │
└─────────────────────────────────┘
```

- ✅ Prix de départ (barré si réduction)
- ✅ Prix final avec réduction
- ✅ Badge avec pourcentage de réduction
- ✅ Montant économisé

---

## 🎯 Exemples Concrets

### Exemple 1 : Pantalon dans "Mode"

1. **Créer catégorie "Mode"** avec icône T-shirt
   - ✅ Attributs créés : Taille + Couleur

2. **Créer produit "Pantalon Classique"**
   - Catégorie : Mode
   - Prix : 5000 FCFA
   - Réduction : 20% (via promotion)

3. **Créer variations** :
   - M + Noir → Stock: 10, Prix: 5000
   - M + Blanc → Stock: 8, Prix: 5000
   - L + Noir → Stock: 15, Prix: 5000
   - etc.

4. **Client sur la page produit** :
   - Voit "Options du produit"
   - Sélectionne Taille: M
   - Sélectionne Couleur: Noir
   - Voit prix : 5000 FCFA (ou 4000 FCFA si réduction de 20%)
   - Clique "Ajouter au panier"

---

### Exemple 2 : Baskets dans "Baskets"

1. **Créer catégorie "Baskets"** avec icône Chaussures
   - ✅ Attributs créés : Pointure + Couleur

2. **Créer produit "Baskets Nike"**
   - Catégorie : Baskets
   - Prix : 15000 FCFA

3. **Créer variations** :
   - Pointure 40 + Noir → Stock: 10
   - Pointure 41 + Noir → Stock: 12
   - Pointure 40 + Blanc → Stock: 8
   - etc.

4. **Client** :
   - Voit Pointure (36-46) et Couleur
   - Sélectionne Pointure 42 + Rouge
   - Ajoute au panier

---

## ✅ Résumé

- ✅ **Les icônes définissent les attributs** automatiquement
- ✅ **Mode (T-shirt)** → Taille + Couleur
- ✅ **Baskets (Chaussures)** → Pointure + Couleur
- ✅ **Téléphones** → Modèle + Capacité + Couleur
- ✅ **Les produits héritent** des attributs de leur catégorie
- ✅ **Le client voit les options** sur la page produit
- ✅ **Prix avec réduction** bien affiché

**Tout est automatique ! Il suffit de choisir l'icône lors de la création de la catégorie.** 🎉

