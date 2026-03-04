# 🚀 Guide de Lancement - Projet Sakha

## Prérequis
- PHP 8.2 ou supérieur
- Composer installé
- Node.js et npm installés
- SQLite (ou MySQL/PostgreSQL)

## Étapes de lancement

### 1. Installer les dépendances PHP
```powershell
composer install
```

### 2. Configurer l'environnement
```powershell
# Copier le fichier .env.example vers .env (si pas déjà fait)
copy .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configurer la base de données
Le projet utilise SQLite par défaut. Vérifiez que le fichier existe :
```powershell
# Créer la base de données SQLite si elle n'existe pas
if (!(Test-Path database\database.sqlite)) { New-Item -ItemType File -Path database\database.sqlite }
```

Puis lancer les migrations :
```powershell
php artisan migrate
```

### 4. Créer un utilisateur administrateur (optionnel)
```powershell
php artisan db:seed --class=AdminUserSeeder
```

### 5. Installer les dépendances Node.js
```powershell
npm install
```

### 6. Compiler les assets (CSS/JS)
```powershell
# Pour le développement (avec hot reload)
npm run dev

# OU pour la production
npm run build
```

### 7. Lancer le serveur de développement
```powershell
php artisan serve
```

Le serveur sera accessible sur : **http://127.0.0.1:8000**

---

## 🎯 Commandes rapides (tout en une fois)

### Option 1 : Script Composer (recommandé)
```powershell
composer run dev
```
Cette commande lance automatiquement :
- Le serveur Laravel
- La queue
- Les logs (Pail)
- Vite (compilation des assets)

### Option 2 : Manuellement
```powershell
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Compilation des assets (si besoin)
npm run dev
```

---

## 🔧 Configuration supplémentaire

### Variables d'environnement importantes (.env)
```env
APP_NAME="Sakha"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Pour les paiements (optionnel)
STRIPE_SECRET=sk_test_...
WAVE_API_URL=...
ORANGE_API_URL=...
```

### Créer un utilisateur admin manuellement
```powershell
php artisan tinker
```
Puis dans tinker :
```php
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

---

## ✅ Vérification

1. Accédez à : http://127.0.0.1:8000
2. Pour le dashboard admin : http://127.0.0.1:8000/admin
3. Connectez-vous avec un compte admin

---

## 🐛 Dépannage

### Erreur "Class not found"
```powershell
composer dump-autoload
```

### Erreur de cache
```powershell
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Erreur de permissions (stockage)
```powershell
php artisan storage:link
```

### Réinitialiser la base de données
```powershell
php artisan migrate:fresh --seed
```

