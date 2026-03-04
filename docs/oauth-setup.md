# Configuration requise pour l'authentification Google

## Variables d'environnement à ajouter dans votre fichier .env :

```bash
# Google OAuth Configuration
GOOGLE_CLIENT_ID=votre_client_id_google
GOOGLE_CLIENT_SECRET=votre_client_secret_google
GOOGLE_REDIRECT=http://localhost:8000/auth/callback/google

# Optionnel - Facebook OAuth
FACEBOOK_CLIENT_ID=votre_client_id_facebook
FACEBOOK_CLIENT_SECRET=votre_client_secret_facebook
FACEBOOK_REDIRECT=http://localhost:8000/auth/callback/facebook
```

## Étapes de configuration Google OAuth :

1. **Créer un projet Google Cloud Console**
   - Allez sur https://console.cloud.google.com/
   - Créez un nouveau projet ou sélectionnez un projet existant

2. **Activer les APIs**
   - Allez dans "APIs & Services" > "Library"
   - Cherchez et activez "Google+ API" ou "People API"

3. **Créer les identifiants OAuth**
   - Allez dans "APIs & Services" > "Credentials"
   - Cliquez sur "Create Credentials" > "OAuth client ID"
   - Sélectionnez "Web application"
   - Ajoutez les URIs de redirection autorisés :
     - `http://localhost:8000/auth/callback/google` (développement)
     - `https://votredomaine.com/auth/callback/google` (production)

4. **Copier les identifiants**
   - Copiez le Client ID et Client Secret dans votre fichier .env

## Problèmes courants et solutions :

### Problème 1 : "redirect_uri_mismatch"
- **Cause** : L'URI de redirection ne correspond pas à celle configurée dans Google Console
- **Solution** : Vérifiez que GOOGLE_REDIRECT correspond exactement à l'URI autorisée dans Google Console

### Problème 2 : "invalid_client"
- **Cause** : Client ID ou Client Secret incorrect
- **Solution** : Vérifiez que les identifiants sont correctement copiés dans .env

### Problème 3 : Package Socialite non installé
- **Solution** : Exécutez `composer require laravel/socialite`

### Problème 4 : Routes non accessibles
- **Vérifiez** que les routes social sont bien dans routes/web.php :
```php
Route::get('/auth/redirect/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/callback/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])->name('social.callback');
```

## Test de l'authentification :

1. Redémarrez votre serveur Laravel après avoir modifié le .env
2. Allez sur la page de login
3. Cliquez sur le bouton "Google"
4. Vous devriez être redirigé vers Google pour l'authentification

## Débogage :

Pour activer le débogage détaillé, ajoutez temporairement dans votre contrôleur :
```php
\Log::info('Google OAuth attempt', [
    'provider' => $provider,
    'client_id' => config('services.google.client_id'),
    'redirect' => config('services.google.redirect')
]);
```
