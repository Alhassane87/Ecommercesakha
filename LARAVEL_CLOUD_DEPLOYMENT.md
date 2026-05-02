# Guide de Déploiement sur Laravel Cloud

## Prérequis

1. **Compte Laravel Cloud** : https://laravel.cloud
2. **Laravel CLI** ou **Git** pour pousser le code
3. **Variables d'environnement** : Clés API, secrets, credentials

## Étape 1 : Créer un Projet sur Laravel Cloud

Visitez https://laravel.cloud et créez un nouveau projet.

## Étape 2 : Configurer les Variables d'Environnement

Dans le dashboard Laravel Cloud, allez à **Settings > Environment Variables** et configurez :

### Variables Requises

```
APP_NAME=Sakha
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (Laravel Cloud fournit automatiquement)
DB_HOST=provided-by-cloud
DB_DATABASE=sakha
DB_USERNAME=provided-by-cloud
DB_PASSWORD=provided-by-cloud

# Redis (pour cache et queue)
REDIS_HOST=provided-by-cloud
REDIS_PASSWORD=provided-by-cloud
REDIS_PORT=6379
```

### Variables Optionnelles (selon vos besoins)

#### Mail
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yourdomain.com
```

#### Stripe
```
STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

#### Google OAuth
```
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_CLIENT_REDIRECT=https://your-domain.com/auth/google/callback
```

#### WhatsApp
```
WHATSAPP_BUSINESS_ACCOUNT_ID=your-account-id
WHATSAPP_ACCESS_TOKEN=your-access-token
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your-verify-token
```

#### AWS S3 (pour les fichiers)
```
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

## Étape 3 : Installer Laravel Cloud CLI

```bash
npm install -g @laravel/cloud-cli
```

Ou via Composer :

```bash
composer global require laravel/cloud-cli
```

## Étape 4 : Lier votre Projet Locale

```bash
cd c:\Users\HP\sakha
laravel-cloud link
```

Suivez les instructions pour sélectionner votre projet Cloud.

## Étape 5 : Déployer

### Option A : Via Git (Recommandé)

1. Connectez votre repository GitHub :
   ```bash
   laravel-cloud github:connect
   ```

2. Configurez le déploiement automatique sur le push vers `main` :
   - Allez à **Deployments > Auto Deploy**
   - Sélectionnez la branche `main`

3. Poussez votre code :
   ```bash
   git add .
   git commit -m "Deploy to Laravel Cloud"
   git push origin main
   ```

### Option B : Via CLI (Déploiement Manuel)

```bash
laravel-cloud deploy
```

## Étape 6 : Vérifier le Déploiement

```bash
# Voir les logs de déploiement
laravel-cloud logs

# Voir le statut du déploiement
laravel-cloud status

# Accéder à votre application
laravel-cloud open
```

## Étape 7 : Configurer une Base de Données (Optionnel)

Si vous avez besoin d'une base de données MySQL/PostgreSQL gérée :

1. Dans le dashboard Cloud, allez à **Databases**
2. Créez une nouvelle base de données
3. Les credentials seront automatiquement ajoutées à vos variables d'environnement

## Étape 8 : Configurer Domain & SSL

1. Allez à **Settings > Domains**
2. Ajoutez votre domaine personnalisé
3. SSL sera automatiquement configuré via Let's Encrypt

## Commandes Utiles

```bash
# Voir les logs en temps réel
laravel-cloud logs --follow

# Exécuter une commande artisan sur le serveur
laravel-cloud artisan migrate

# Redémarrer l'application
laravel-cloud restart

# Voir les environnement variables
laravel-cloud env:list

# Ajouter une variable d'environnement
laravel-cloud env:set KEY=value

# Arrêter le déploiement
laravel-cloud deploy:cancel

# Revenir à un déploiement précédent
laravel-cloud deploy:rollback
```

## Troubleshooting

### Les logs montrent des erreurs de base de données

Assurez-vous que :
1. `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` sont correctement configurés
2. La migration `php artisan migrate` s'exécute au déploiement ✓ (configuré dans `laravel.yml`)

### Les fichiers uploadés ne se sauvegardent pas

1. Configurez AWS S3 dans les variables d'environnement
2. Changez `FILESYSTEM_DISK=s3` dans `.env.production`
3. Assurez-vous que le bucket S3 est accessible

### Les emails ne sont pas envoyés

1. Vérifiez les credentials SMTP
2. Utilisez un service comme **Mailtrap**, **Postmark** ou **Resend**
3. Testez localement d'abord

### La queue ne traite pas les jobs

1. Vérifiez que `QUEUE_CONNECTION=redis` ou `database`
2. Vérifiez les logs : `laravel-cloud logs --follow`
3. Les workers sont automatiquement gérés sur Laravel Cloud

## Architecture de Déploiement

```
┌─────────────────────────────────────────┐
│         Laravel Cloud Platform           │
├─────────────────────────────────────────┤
│  ┌─────────────┐  ┌──────────────────┐  │
│  │   Web App   │  │  Background Jobs │  │
│  │  (FrankenPHP)   │  (Queue Workers) │  │
│  └─────────────┘  └──────────────────┘  │
│        ↓                     ↓            │
│  ┌─────────────────────────────────────┐ │
│  │  Redis (Cache, Session, Queue)      │ │
│  └─────────────────────────────────────┘ │
│  ┌─────────────────────────────────────┐ │
│  │  MySQL Database                     │ │
│  └─────────────────────────────────────┘ │
│  ┌─────────────────────────────────────┐ │
│  │  AWS S3 (File Storage)              │ │
│  └─────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

## Coûts Estimés

- **Départ gratuit** : $5/mois pour un environnement minimal
- **Ajouter une BD MySQL** : ~$10/mois
- **Redis** : ~$5-10/mois
- **Trafic supplémentaire** : Tarification au dépassement

## Prochaines Étapes

1. ✅ Créer un compte Laravel Cloud
2. ✅ Configurer les variables d'environnement
3. ✅ Connecter GitHub ou déployer via CLI
4. ✅ Vérifier les logs et faire un test d'accès
5. ✅ Configurer un domaine personnalisé
6. ✅ Mettre en place les backups automatiques

**Besoin d'aide ?** Consultez la documentation : https://laravel.cloud/docs
