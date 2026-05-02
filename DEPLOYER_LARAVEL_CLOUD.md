# 🚀 Guide Complet : Déployer Sakha sur Laravel Cloud

## ✅ Prérequis (Vérification)

- [x] **Repository GitHub** : https://github.com/Alhassane87/Ecommercesakha
- [x] **Configuration Laravel Cloud** : `laravel.yml` ✅
- [x] **Variables d'environnement** : `.env.production` ✅
- [x] **Documentation** : Guides disponibles ✅
- [ ] **Compte Laravel Cloud** : À créer

---

## 🎯 ÉTAPE 1 : Créer un Compte Laravel Cloud

### 1.1 - Aller sur Laravel Cloud

**URL** : https://laravel.cloud

### 1.2 - S'enregistrer ou se connecter

Cliquez sur **"Sign Up"** (ou **"Sign In"** si vous avez déjà un compte)

**Options d'inscription :**
- GitHub (Recommandé - plus facile)
- Email + Mot de passe

**Cliquez sur "Sign in with GitHub"** ➜ Autorisez l'accès

### 1.3 - Créer votre première organisation

Après la connexion, Laravel Cloud vous demande de créer une organisation :

```
Organization Name: Sakha
Website: https://sakha.com  (ou laissez vide)
```

➜ Cliquez **"Create Organization"**

---

## 🎯 ÉTAPE 2 : Créer un Projet

### 2.1 - Dans le Dashboard

Allez à **"Projects"** ➜ **"Create Project"**

Remplissez :
```
Project Name: Sakha
Environment Name: production
Region: Frankfurt (ou la région la plus proche de vos utilisateurs)
```

➜ Cliquez **"Create Project"**

⏳ Attendre quelques secondes...

---

## 🎯 ÉTAPE 3 : Connecter GitHub

### 3.1 - Autoriser GitHub

Dans le dashboard, allez à **"Settings"** ➜ **"Repository"**

Cliquez **"Connect Repository"**

Vous serez redirigé vers GitHub pour donner accès à Laravel Cloud.

**Cliquez "Authorize laravel/cloud"**

### 3.2 - Sélectionner votre Repository

Après l'autorisation, revenant sur Laravel Cloud :

```
Repository: Alhassane87 / Ecommercesakha
Branch: main
```

➜ Cliquez **"Connect Repository"**

✅ **Votre repo est maintenant lié !**

---

## 🎯 ÉTAPE 4 : Configurer les Variables d'Environnement

### 4.1 - Aller à Environment Variables

Dans le dashboard : **"Settings"** ➜ **"Environment Variables"**

### 4.2 - Ajouter les Variables (Ordre Recommandé)

#### **Groupe 1 : Configuration de Base**

Cliquez **"+ Add Variable"** pour chaque ligne :

```
KEY: APP_NAME
VALUE: Sakha
SAVE ✓

KEY: APP_ENV
VALUE: production
SAVE ✓

KEY: APP_DEBUG
VALUE: false
SAVE ✓

KEY: APP_URL
VALUE: https://sakha.laravel.cloud
SAVE ✓

KEY: LOG_CHANNEL
VALUE: stack
SAVE ✓

KEY: LOG_LEVEL
VALUE: error
SAVE ✓
```

#### **Groupe 2 : Base de Données**

✅ **Laravel Cloud crée automatiquement** :
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

**Ne les modifiez pas !** ➜ Allez à l'onglet "Database" pour voir les credentials créés

#### **Groupe 3 : Cache & Session**

```
KEY: CACHE_STORE
VALUE: redis
SAVE ✓

KEY: SESSION_DRIVER
VALUE: redis
SAVE ✓

KEY: QUEUE_CONNECTION
VALUE: redis
SAVE ✓
```

#### **Groupe 4 : Mail (Mailtrap ou Resend)**

**Option A : Mailtrap**

```
KEY: MAIL_MAILER
VALUE: smtp
SAVE ✓

KEY: MAIL_HOST
VALUE: smtp.mailtrap.io
SAVE ✓

KEY: MAIL_PORT
VALUE: 2525
SAVE ✓

KEY: MAIL_USERNAME
VALUE: <votre-username>
SAVE ✓

KEY: MAIL_PASSWORD
VALUE: <votre-password>
SAVE ✓

KEY: MAIL_ENCRYPTION
VALUE: tls
SAVE ✓

KEY: MAIL_FROM_ADDRESS
VALUE: hello@sakha.com
SAVE ✓

KEY: MAIL_FROM_NAME
VALUE: Sakha
SAVE ✓
```

**Comment obtenir Mailtrap credentials :**
1. Allez à https://mailtrap.io
2. Créez un compte gratuit
3. Créez une "Inbox"
4. Allez à **Integrations > SMTP Settings**
5. Copiez les credentials

**Option B : Resend (Plus simple et moderne)**

```
KEY: MAIL_MAILER
VALUE: resend
SAVE ✓

KEY: RESEND_API_KEY
VALUE: re_...
SAVE ✓

KEY: MAIL_FROM_ADDRESS
VALUE: hello@sakha.com
SAVE ✓

KEY: MAIL_FROM_NAME
VALUE: Sakha
SAVE ✓
```

Obtenez votre clé API : https://resend.com

#### **Groupe 5 : Stripe (Paiements)**

```
KEY: STRIPE_PUBLIC_KEY
VALUE: pk_live_...
SAVE ✓

KEY: STRIPE_SECRET_KEY
VALUE: sk_live_...
SAVE ✓

KEY: STRIPE_WEBHOOK_SECRET
VALUE: whsec_...
SAVE ✓
```

Obtenez vos clés : https://dashboard.stripe.com/apikeys

#### **Groupe 6 : Google OAuth (Optionnel)**

```
KEY: GOOGLE_CLIENT_ID
VALUE: xxxxx.apps.googleusercontent.com
SAVE ✓

KEY: GOOGLE_CLIENT_SECRET
VALUE: xxxx_xxxx
SAVE ✓

KEY: GOOGLE_CLIENT_REDIRECT
VALUE: https://sakha.laravel.cloud/auth/google/callback
SAVE ✓
```

Créez un projet : https://console.cloud.google.com

#### **Groupe 7 : WhatsApp (Optionnel)**

```
KEY: WHATSAPP_BUSINESS_ACCOUNT_ID
VALUE: votre-account-id
SAVE ✓

KEY: WHATSAPP_ACCESS_TOKEN
VALUE: EAA...
SAVE ✓

KEY: WHATSAPP_WEBHOOK_VERIFY_TOKEN
VALUE: votre_verify_token_secret
SAVE ✓
```

---

## 🎯 ÉTAPE 5 : Vérifier la Configuration

Après avoir ajouté toutes les variables, allez à **"Configuration"** et vérifiez que tout est listé.

Vous devriez voir environ **20-25 variables** (selon vos choix).

---

## 🎯 ÉTAPE 6 : Déclencher le Premier Déploiement

### 6.1 - Via Auto-Deploy

**Option 1 : Déploiement Automatique** (Recommandé)

Allez à **"Deployments"** ➜ **"Auto Deploy"**

Sélectionnez : **"Deploy on every push to main"**

➜ Cliquez **"Enable"**

**Maintenant, chaque push vers `main` déploiera automatiquement !**

### 6.2 - Ou Déclencher Manuel

Allez à **"Deployments"** ➜ **"Deploy"**

Cliquez le bouton **"Deploy"**

⏳ **Attendre 3-5 minutes** pour que le déploiement se termine

---

## 🎯 ÉTAPE 7 : Vérifier le Déploiement

### 7.1 - Voir les Logs

Allez à **"Deployments"** ➜ Cliquez sur le dernier déploiement

Vous verrez les logs en temps réel :

```
✓ Building Docker image
✓ Installing dependencies
✓ Running migrations
✓ Building assets
✓ Starting processes
```

### 7.2 - Vérifier que tout a marché

À la fin, vous devriez voir :

```
✅ Deployment successful!
🌐 Your application is live at: https://sakha.laravel.cloud
```

### 7.3 - Accéder à votre app

**Allez à :** https://sakha.laravel.cloud

**Vous devriez voir votre application en direct !** 🎉

---

## 🎯 ÉTAPE 8 : Configurer un Domaine Personnalisé (Optionnel)

### 8.1 - Ajouter un Domaine

Allez à **"Settings"** ➜ **"Domains"**

Cliquez **"+ Add Domain"**

Entrez votre domaine :
```
Domain: sakha.com
```

### 8.2 - Configurer le DNS

Laravel Cloud vous donnera les instructions DNS :

Chez votre registrar de domaine (GoDaddy, Namecheap, etc.) :
- Allez au gestionnaire DNS
- Ajoutez un enregistrement CNAME pointant vers `sakha.laravel.cloud`
- Ou suivez les instructions spécifiques de Laravel Cloud

⏳ Attendre 15-30 minutes pour la propagation DNS

✅ SSL sera automatiquement activé via Let's Encrypt

---

## 🔍 Troubleshooting

### ❌ Déploiement échoue lors des migrations

**Erreur** :
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solution** :
1. Vérifiez que `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` sont corrects
2. Allez à **"Database"** dans les settings
3. Vérifiez que la BD est en cours d'exécution

### ❌ Les emails ne s'envoient pas

**Solution** :
1. Vérifiez les credentials Mailtrap/Resend
2. Allez à **"Logs"** pour voir les erreurs
3. Testez manuellement : 
   ```bash
   laravel-cloud artisan tinker
   Mail::raw('test', fn($m) => $m->to('you@example.com')->subject('test'));
   ```

### ❌ Le site retourne une erreur 500

**Solution** :
1. Vérifiez les logs : **"Deployments"** ➜ **"View Logs"**
2. Vérifiez que `APP_KEY` est défini
3. Exécutez : `laravel-cloud artisan config:cache`

### ❌ Les fichiers uploadés disparaissent

**Solution** :
1. Configurez AWS S3
2. Changez `FILESYSTEM_DISK=s3`
3. Ajouter les credentials S3

---

## 📱 Commandes Utiles (Optionnel)

Si vous installez Laravel Cloud CLI :

```bash
npm install -g @laravel/cloud-cli
```

Alors vous pouvez faire :

```bash
# Voir les logs
laravel-cloud logs --follow

# Exécuter une commande artisan
laravel-cloud artisan migrate

# Redémarrer
laravel-cloud restart

# Ajouter une variable
laravel-cloud env:set KEY=value
```

---

## ✅ Checklist de Déploiement

- [ ] Compte Laravel Cloud créé
- [ ] Organisation créée
- [ ] Projet créé
- [ ] GitHub connecté
- [ ] Variables d'environnement ajoutées (au minimum : APP_NAME, APP_ENV, APP_DEBUG, APP_URL, Mail)
- [ ] Auto-deploy activé ou déploiement manuel déclenché
- [ ] Logs vérifiés
- [ ] Application accessible
- [ ] Domaine personnalisé configuré (optionnel)
- [ ] SSL activé automatiquement

---

## 🎉 Bravo !

Votre application **Sakha** est maintenant **EN LIGNE** sur Laravel Cloud ! 🚀

---

## 📚 Ressources Utiles

- **Laravel Cloud Docs** : https://laravel.cloud/docs
- **Mailtrap** : https://mailtrap.io
- **Resend** : https://resend.com
- **Stripe Keys** : https://dashboard.stripe.com/apikeys
- **Google Console** : https://console.cloud.google.com
- **AWS S3** : https://console.aws.amazon.com

---

## 🆘 Besoin d'aide ?

Contactez le support Laravel Cloud : https://laravel.cloud/support

Ou consultez les logs de déploiement pour voir les erreurs exactes.

**Bonne chance ! 🚀**
