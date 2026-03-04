# 🔧 Correction immédiate de l'erreur Google OAuth

## ❌ Erreur actuelle
```
Missing required parameter: redirect_uri
Erreur 400 : invalid_request
```

## ✅ Solution immédiate

### 1. Ajoutez ces lignes dans votre fichier `.env` :

```bash
# Google OAuth Configuration
GOOGLE_CLIENT_ID=votre_client_id_google_ici
GOOGLE_CLIENT_SECRET=votre_client_secret_google_ici  
GOOGLE_REDIRECT=http://localhost:8000/auth/callback/google
```

### 2. Redémarrez votre serveur Laravel :
```bash
php artisan serve
```

### 3. Configurez Google Cloud Console :

1. Allez sur https://console.cloud.google.com/
2. Sélectionnez votre projet
3. Allez dans "APIs & Services" > "Credentials"
4. Éditez votre "OAuth 2.0 Client IDs"
5. Dans "Authorized redirect URIs", ajoutez :
   - `http://localhost:8000/auth/callback/google`

### 4. Si vous n'avez pas de projet Google :

1. Créez un projet sur https://console.cloud.google.com/
2. Activez "Google+ API" ou "People API"
3. Créez des identifiants OAuth 2.0
4. Ajoutez l'URI de redirection ci-dessus

## 🧪 Test rapide

Après configuration :
1. Allez sur votre page de login
2. Cliquez sur "Se connecter avec Google"
3. Vous devriez être redirigé vers Google

## 🔍 Débogage

Si l'erreur persiste, vérifiez :

1. **Dans votre .env** :
   ```bash
   php artisan tinker
   >>> config('services.google.client_id')
   >>> config('services.google.redirect')
   ```

2. **URL exacte** dans Google Console doit être :
   ```
   http://localhost:8000/auth/callback/google
   ```

3. **Pas de slash final** dans l'URL

## 📋 Étapes rapides

1. ✅ Copiez les variables dans `.env`
2. ✅ Configurez Google Console avec l'URL exacte
3. ✅ Redémarrez le serveur
4. ✅ Testez l'authentification

Le problème sera résolu !
