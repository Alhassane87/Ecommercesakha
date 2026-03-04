# 🛒 Guide : Options Produits pour les Clients

## ✅ Ce qui est déjà en place

### Sur la page produit (`/product/{slug}`)

Quand un client visite une page produit, il voit automatiquement :

1. **Section "Options du produit"** (si des attributs sont configurés)
   - Titre clair : "Options du produit"
   - Message : "Sélectionnez vos options avant d'ajouter au panier"
   - Chaque attribut dans une carte séparée

2. **Sélection des options** :
   - **Pointure** (pour baskets) : Boutons avec les tailles (36, 37, 38, etc.)
   - **Couleur** : Cercles colorés avec le code couleur
   - **Taille** (pour vêtements) : Boutons S, M, L, XL, XXL
   - **Modèle** (pour téléphones) : Liste des modèles disponibles
   - **Capacité** (pour téléphones) : 64GB, 128GB, 256GB, etc.

3. **Validation** :
   - Les options obligatoires sont marquées avec un *
   - Le bouton "Ajouter au panier" est désactivé tant que toutes les options requises ne sont pas sélectionnées
   - Message d'erreur si une option obligatoire n'est pas sélectionnée

4. **Affichage du stock** :
   - Quand une combinaison d'options est sélectionnée, le stock disponible s'affiche
   - Le prix peut changer selon la variation sélectionnée

---

## 🎯 Comment ça fonctionne pour le client

### Exemple : Achat de Baskets

1. Le client va sur la page d'un produit "Baskets Nike"
2. Il voit la section **"Options du produit"** avec :
   - **Pointure** : 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46
   - **Couleur** : Cercles colorés (Noir, Blanc, Rouge, Bleu)
3. Le client clique sur **Pointure 42** → Le bouton devient bleu (sélectionné)
4. Le client clique sur **Rouge** → Le cercle rouge est sélectionné
5. Le système trouve automatiquement la variation correspondante
6. Le stock disponible s'affiche : "Stock disponible : 15"
7. Le bouton **"Ajouter au panier"** devient actif
8. Le client clique sur "Ajouter au panier"
9. Le produit est ajouté avec les options sélectionnées (Pointure 42, Rouge)

### Exemple : Achat de Téléphone

1. Le client va sur "iPhone 15"
2. Il voit les options :
   - **Modèle** : iPhone 15, iPhone 15 Pro, iPhone 15 Pro Max
   - **Capacité** : 64GB, 128GB, 256GB, 512GB
   - **Couleur** : Noir, Blanc, Bleu, Rose
3. Le client sélectionne : Modèle "iPhone 15 Pro", Capacité "256GB", Couleur "Bleu"
4. Le prix peut changer selon la capacité sélectionnée
5. Le stock s'affiche pour cette combinaison
6. Le client ajoute au panier

---

## ⚠️ Important pour l'admin

Pour que les clients voient les options, vous devez :

1. **Créer des attributs pour la catégorie** :
   - Aller dans Admin → Catégories
   - Éditer la catégorie (ex: "Baskets")
   - Ajouter les attributs (Pointure, Couleur)

2. **Créer des variations pour le produit** :
   - Éditer le produit
   - Créer des variations avec les combinaisons d'options
   - Définir le stock et le prix pour chaque variation

3. **Tester** :
   - Aller sur la page produit en tant que visiteur
   - Vérifier que les options s'affichent
   - Sélectionner des options et ajouter au panier

---

## 🎨 Affichage visuel

- **Pointures/Tailles** : Boutons compacts avec style distinct
- **Couleurs** : Cercles colorés avec le code hex
- **Capacités** : Badges avec style distinct
- **Modèles** : Boutons standards
- **Sélection** : Bordure bleue + ring + fond bleu clair
- **Erreur** : Message rouge si option obligatoire non sélectionnée

---

## ✅ Le système est prêt !

Les clients peuvent maintenant :
- ✅ Voir les options disponibles pour chaque produit
- ✅ Sélectionner leurs préférences (pointure, couleur, etc.)
- ✅ Voir le stock disponible pour leur sélection
- ✅ Ajouter au panier avec les options choisies

**Tout fonctionne automatiquement !** 🎉

