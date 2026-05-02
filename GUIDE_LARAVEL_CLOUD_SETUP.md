# Guide Complet : Configuration de Sakha sur Laravel Cloud

## 🚀 Étape 1 : Créer un Compte et un Projet

1. **Accédez à Laravel Cloud** : https://laravel.cloud
2. **Créez un compte** (ou connectez-vous)
3. **Créez un nouveau projet** :
   - Nom : `sakha`
   - Environnement par défaut : `production`
   - Région : Choisissez la plus proche de vos utilisateurs

## 🔗 Étape 2 : Connecter votre Repository GitHub

1. Dans le dashboard, allez à **Settings > GitHub**
2. Cliquez **Connect GitHub**
3. Autorisez l'accès à votre compte GitHub
4. Sélectionnez : `Alhassane87/Ecommercesakha`
5. Configurez le **Auto Deploy** sur la branche `main`

---

## 🔐 Étape 3 : Configurer les Variables d'Environnement

### Dashboard > Settings > Environment Variables

Ajoutez les variables suivantes (copiez-collez chaque ligne) :

### 🔹 Groupe 1 : Configuration de Base

```
APP_NAME=Sakha
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sakha.laravel.cloud
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Comment ajouter :**
- Cliquez "+ Add Variable"
- Collez `APP_NAME` dans "Key"
- Collez `Sakha` dans "Value"
- Cliquez "Save"
- Répétez pour chaque variable

### 🔹 Groupe 2 : Base de Données

Laravel Cloud crée automatiquement une BD MySQL. Vous verrez ces variables :

```
DB_CONNECTION=mysql
DB_HOST=<auto>
DB_PORT=3306
DB_DATABASE=<auto>
DB_USERNAME=<auto>
DB_PASSWORD=<auto>
```

✅ **Ces variables sont créées automatiquement, ne les modifiez pas**

### 🔹 Groupe 3 : Cache & Session (Redis)

```
CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis
```

### 🔹 Groupe 4 : Redis

Laravel Cloud fournit automatiquement :

```
REDIS_HOST=<auto>
REDIS_PASSWORD=<auto>
REDIS_PORT=6379
```

✅ **Aussi automatiques**

### 🔹 Groupe 5 : Mail (Choisir une option)

#### Option A : Mailtrap (Recommandé pour tester)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<votre-username>
MAIL_PASSWORD=<votre-password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@sakha.com
MAIL_FROM_NAME=Sakha
```

**Comment obtenir les credentials Mailtrap :**
1. Allez sur https://mailtrap.io
2. Créez un compte gratuit
3. Créez une "Inbox"
4. Allez à **Integrations > SMTP Settings**
5. Copiez Username et Password

#### Option B : Resend (Moderne)

```
MAIL_MAILER=resend
RESEND_API_KEY=<votre-clé-api>
MAIL_FROM_ADDRESS=hello@sakha.com
MAIL_FROM_NAME=Sakha
```

### 🔹 Groupe 6 : Stripe (Paiements)

Obtenez vos clés sur https://dashboard.stripe.com/apikeys

```
STRIPE_PUBLIC_KEY=pk_live_xxxxx...
STRIPE_SECRET_KEY=sk_live_xxxxx...
STRIPE_WEBHOOK_SECRET=whsec_xxxxx...
```

### 🔹 Groupe 7 : Google OAuth

Obtenez vos credentials sur https://console.cloud.google.com

```
GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxx_xxxx_xxxx
GOOGLE_CLIENT_REDIRECT=https://sakha.laravel.cloud/auth/google/callback
```

### 🔹 Groupe 8 : WhatsApp (Meta Cloud API)

```
WHATSAPP_BUSINESS_ACCOUNT_ID=123456789
WHATSAPP_ACCESS_TOKEN=EAA...
WHATSAPP_WEBHOOK_VERIFY_TOKEN=votre_verify_token_secret
```

### 🔹 Groupe 9 : AWS S3 (Stockage de Fichiers)

Créez un bucket S3 et ajoutez ces credentials :

```
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=xxxxx...
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=sakha-uploads
AWS_URL=https://sakha-uploads.s3.amazonaws.com
```

---

## 📊 Résumé des Variables (Ordre Recommandé)

| Groupe | Priorité | Notes |
|--------|----------|-------|
| **Configuration de Base** | ⭐⭐⭐ | Obligatoire |
| **Base de Données** | ⭐⭐⭐ | Auto (ne pas modifier) |
| **Cache & Session** | ⭐⭐ | Important pour la performance |
| **Mail** | ⭐⭐ | Prioriser Mailtrap ou Resend |
| **Stripe** | ⭐⭐ | Si vous vendez en ligne |
| **Google OAuth** | ⭐ | Si vous utilisez login Google |
| **WhatsApp** | ⭐ | Si vous vendez via WhatsApp |
| **AWS S3** | ⭐ | Pour les uploads de fichiers |

---

## ✅ Étape 4 : Vérifier la Configuration

Après avoir ajouté toutes les variables :

1. Cliquez **Deploy** (ou attendez le auto-deploy)
2. Allez à **Deployments** pour voir les logs
3. Attendez le message : ✅ **Deployment successful**

Vérifiez les logs :
```
✓ Running migrations
✓ Building assets
✓ Starting processes
```

---

## 🌐 Étape 5 : Configurer votre Domaine

1. Allez à **Settings > Domains**
2. Cliquez **Add Domain**
3. Entrez votre domaine : `sakha.com` (exemple)
4. Suivez les instructions pour pointer votre DNS
5. SSL sera automatiquement activé via Let's Encrypt

---

## 🧪 Étape 6 : Tester l'Application

```bash
# Voir les logs en temps réel
laravel-cloud logs --follow

# Exécuter une commande artisan
laravel-cloud artisan tinker

# Vérifier l'état du déploiement
laravel-cloud status

# Ouvrir l'application
laravel-cloud open
```

---

## 🔧 Commandes Utiles

```bash
# Installer Laravel Cloud CLI
npm install -g @laravel/cloud-cli

# Voir les logs
laravel-cloud logs --follow

# Exécuter une migration
laravel-cloud artisan migrate

# Exécuter une commande artisan
laravel-cloud artisan seed

# Voir les variables d'environnement
laravel-cloud env:list

# Ajouter une variable (depuis CLI)
laravel-cloud env:set KEY=value

# Redémarrer l'application
laravel-cloud restart

# Revenir à un déploiement antérieur
laravel-cloud deploy:rollback
```

---

## 🐛 Troubleshooting

### Les emails ne s'envoient pas
✅ Vérifiez les credentials Mailtrap/Resend  
✅ Vérifiez `MAIL_FROM_ADDRESS` est valide  
✅ Vérifiez les logs : `laravel-cloud logs --follow`

### Erreur de base de données
✅ Vérifiez que les migrations s'exécutent  
✅ Exécutez : `laravel-cloud artisan migrate`  
✅ Vérifiez les logs d'erreur

### Les fichiers uploadés disparaissent
✅ Configurez AWS S3  
✅ Changez `FILESYSTEM_DISK=s3`  
✅ Vérifiez les permissions du bucket S3

### La queue ne traite pas les jobs
✅ Vérifiez `QUEUE_CONNECTION=redis`  
✅ Vérifiez `REDIS_HOST` et `REDIS_PASSWORD`  
✅ Vérifiez les logs de la queue

---

## 📈 Prochaines Étapes

1. **Backups** : Configurez les backups automatiques (Settings > Backups)
2. **Monitoring** : Activez les alertes (Settings > Alerts)
3. **Domaine personnalisé** : Configurez votre domaine (Settings > Domains)
4. **SSL** : Déjà automatique via Let's Encrypt
5. **Performance** : Activez le caching (Redis + Laravel caching)

---

## 💰 Estimation des Coûts

| Service | Coût |
|---------|------|
| App (Web + Queue) | $5-10/mois |
| Base de Données MySQL | $10/mois |
| Redis | $5-10/mois |
| Storage (100 GB) | ~$2-5/mois |
| **TOTAL ESTIMÉ** | **$22-35/mois** |

*Les coûts peuvent varier selon la charge. Consultez https://laravel.cloud/pricing*

---

## 📚 Ressources Utiles

- **Docs Laravel Cloud** : https://laravel.cloud/docs
- **Mailtrap** : https://mailtrap.io
- **Stripe Keys** : https://dashboard.stripe.com/apikeys
- **Google OAuth** : https://console.cloud.google.com
- **AWS S3** : https://console.aws.amazon.com
- **Meta WhatsApp** : https://developers.facebook.com/docs/whatsapp/cloud-api

---

## 🎯 Checklist de Déploiement

- [ ] Repository GitHub liée
- [ ] Auto-deploy activé
- [ ] Variables de base configurées (APP_NAME, APP_ENV, etc.)
- [ ] Mail configuré (Mailtrap ou Resend)
- [ ] Stripe configuré (si applicable)
- [ ] AWS S3 configuré (si applicable)
- [ ] Google OAuth configuré (si applicable)
- [ ] WhatsApp configuré (si applicable)
- [ ] Première déploiement réussi
- [ ] Application accessible sur le web
- [ ] Domaine personnalisé configuré
- [ ] SSL actif
- [ ] Backups activés

---

**Besoin d'aide ?** Contactez support Laravel Cloud : https://laravel.cloud/support
