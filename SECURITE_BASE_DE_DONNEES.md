# Guide Sécurisation de la Base de Données

## 🚨 Problème Actuel

Votre BD utilise des credentials par défaut/faibles :
- **Utilisateur local** : `root` (pas de mot de passe)
- **En production** : Ces credentials ne doivent JAMAIS être exposés

---

## ✅ Étape 1 : Sécuriser la BD Locale (Développement)

### 1.1 - Changer le mot de passe root MySQL

```bash
mysql -u root
```

Puis dans MySQL :

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'votre_nouveau_mot_de_passe_fort';
FLUSH PRIVILEGES;
EXIT;
```

Exemple de mot de passe fort :
```
MyS@kh@Db2024!Secure#Pass
```

### 1.2 - Créer un utilisateur dédié (Recommandé)

```sql
-- Se connecter avec le nouveau mot de passe root
mysql -u root -p

-- Créer un utilisateur sakha
CREATE USER 'sakha_dev'@'localhost' IDENTIFIED BY 'Sakha@Dev2024!Secure';

-- Donner les permissions
GRANT ALL PRIVILEGES ON sakha.* TO 'sakha_dev'@'localhost';

-- Rafraîchir
FLUSH PRIVILEGES;
EXIT;
```

### 1.3 - Mettre à jour votre `.env` local

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sakha
DB_USERNAME=sakha_dev
DB_PASSWORD=Sakha@Dev2024!Secure
```

---

## 🔐 Étape 2 : Sécuriser la BD en Production (Laravel Cloud)

**IMPORTANT** : Laravel Cloud gère automatiquement la sécurité de la BD.

Mais vous devez configurer les variables d'environnement correctement.

### 2.1 - Générer des Credentials Forts

Utilisez ce script pour générer des mots de passe sécurisés :

```powershell
# PowerShell - Générer un mot de passe sécurisé
$Password = -join ((33..126) | Get-Random -Count 32 | % {[char]$_})
Write-Host "Password: $Password"
```

Ou allez sur : https://www.random.org/passwords/

**Format recommandé :**
- Minimum 32 caractères
- Majuscules + minuscules + chiffres + symboles
- Pas d'espace ni de caractères spéciaux comme `$` ou `\` (problématiques en shell)

Exemples valides :
```
AkX9mPq2nRs3tUv4wXy5zAbCdEfGhIjK1l2m3n4o5p6q7r8s9t
Sakha!2024@Production#Db$Password&Secure*New
MyApp_DB_Prod_123!456@789#Secure_Key_New_2024
```

---

## 📋 Étape 3 : Configurer Laravel Cloud

### Via le Dashboard

1. **Allez à** : https://laravel.cloud/dashboard
2. **Settings > Environment Variables**
3. **Cherchez ces variables** (Laravel Cloud les crée automatiquement) :
   - `DB_HOST` ← Laissez tel quel (auto)
   - `DB_PORT` ← Laissez tel quel (auto)
   - `DB_DATABASE` ← Laissez tel quel (auto)
   - `DB_USERNAME` ← Laissez tel quel (auto)
   - `DB_PASSWORD` ← Laissez tel quel (auto)

✅ **Ces variables sont générées automatiquement par Laravel Cloud et sont sécurisées.**

### Vérification : Votre `.env.production`

Doit ressembler à :

```env
APP_NAME=Sakha
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sakha.com

# Database - Laravel Cloud fournit automatiquement
DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=3306
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

✅ **Les variables sont des placeholders, jamais les vraies valeurs !**

---

## 🔒 Étape 4 : Bonnes Pratiques de Sécurité

### ✅ À Faire

- [ ] **Mots de passe forts** : Min 32 caractères, mélange A-Z, a-z, 0-9, symboles
- [ ] **Utilisateur dédié** : Un utilisateur par application, pas `root`
- [ ] **Permissions minimales** : Seulement les permissions nécessaires
- [ ] **Variables d'environnement** : Jamais dans le code
- [ ] **`.gitignore`** : Tous les `.env*` ignorés
- [ ] **HTTPS** : Toujours activé en production
- [ ] **SSL/TLS** : Pour la connexion à la BD
- [ ] **Backups** : Automatiques et chiffrés
- [ ] **Logs** : Tous les accès BD loggés

### ❌ À Éviter

- ❌ Utiliser `root` en production
- ❌ Pas de mot de passe
- ❌ Mots de passe simples (`123456`, `password`, etc.)
- ❌ Partager les credentials par email
- ❌ Écrire les credentials dans le code
- ❌ Utiliser le même password pour plusieurs apps
- ❌ Garder des credentials dans Git/GitHub

---

## 📝 Commandes Utiles

### Vérifier la connexion MySQL localement

```bash
mysql -u sakha_dev -p sakha
# Entrez le mot de passe
# Si connecté, vous verrez : mysql>
```

### Vérifier les utilisateurs MySQL

```bash
mysql -u root -p
```

```sql
SELECT user, host FROM mysql.user;
EXIT;
```

### Réinitialiser un utilisateur (en cas d'oubli)

```bash
mysql -u root
```

```sql
ALTER USER 'sakha_dev'@'localhost' IDENTIFIED BY 'nouveau_mot_de_passe';
FLUSH PRIVILEGES;
EXIT;
```

---

## 🧪 Test de Sécurité

### 1. Vérifier que `.env` n'est pas commité

```bash
git log --oneline -- .env
git log --oneline -- .env.production
```

Résultat attendu : **Rien** (ou seulement `.env.production` avec variables)

### 2. Vérifier que `.gitignore` fonctionne

```bash
git check-ignore .env
git check-ignore .env.production
```

Résultat attendu :
```
.env (non ignored - c'est OK, c'est un template)
.env.production (ignored)
```

### 3. Tester la connexion avec les nouveaux credentials

```bash
cd c:\Users\HP\sakha
php artisan tinker
# Dans tinker :
DB::connection()->getPdo()
# Si OK, vous verrez une PDO object
```

---

## 🚀 Plan d'Action Recommandé

### Jour 1 : Sécuriser Localement

1. ✅ Générer un mot de passe fort pour `root`
2. ✅ Créer un utilisateur `sakha_dev`
3. ✅ Mettre à jour `.env` avec les nouveaux credentials
4. ✅ Tester que l'app fonctionne

### Jour 2 : Vérifier que tout est sécurisé

1. ✅ Vérifier que `.env` n'est pas dans Git
2. ✅ Vérifier que `.env.production` utilise des variables
3. ✅ Tester `php artisan tinker`

### Jour 3 : Déployer sur Laravel Cloud

1. ✅ Laravel Cloud crée automatiquement une BD sécurisée
2. ✅ Configurer le domaine
3. ✅ Tester l'accès

---

## 📊 Résumé

| Aspect | Local | Production (Laravel Cloud) |
|--------|-------|----------------------------|
| **Utilisateur** | `sakha_dev` | Généré automatiquement |
| **Mot de passe** | Votre mot de passe fort | Généré automatiquement |
| **Stockage** | `.env` (local) | Variables d'environnement |
| **Exposé dans Git ?** | Non (`.gitignore`) | Non (placeholders uniquement) |
| **Sécurité** | Bonne | Excellente (géré par Laravel) |

---

## ❓ Besoin d'aide ?

Dites-moi quelle étape vous bloquez et je vous aide ! 🚀

- **Générer un mot de passe fort ?**
- **Changer le password MySQL ?**
- **Créer un utilisateur dédié ?**
- **Mettre à jour `.env` ?**
- **Tester la connexion ?**
