# 🚀 DÉMARRAGE RAPIDE - CHATBOT IA + WHATSAPP

## ⚡ Installation en 3 étapes

### 1️⃣ Exécutez les migrations
```bash
php artisan migrate
```

### 2️⃣ Copiez les variables d'environnement
Ouvrez `env_chatbot_example.txt` et copiez le contenu dans votre fichier `.env`

### 3️⃣ Obtenez votre clé API Gemini (GRATUIT)
1. Allez sur: https://makersuite.google.com/app/apikey
2. Cliquez sur "Create API Key"
3. Copiez la clé
4. Ajoutez-la dans `.env`:
   ```
   GEMINI_API_KEY=votre_clé_ici
   ```

## ✅ Activation du widget chatbot

Dans `resources/views/layouts/app.blade.php` (ou votre layout principal), ajoutez avant `</body>`:

```blade
@include('components.chatbot-widget')
```

## 🎯 Test rapide

1. Démarrez votre serveur: `php artisan serve`
2. Ouvrez votre site
3. Cliquez sur l'icône de chat en bas à droite
4. Tapez "Bonjour"

## 📱 Activation WhatsApp (Optionnel)

Consultez le guide complet: `GUIDE_CHATBOT_INSTALLATION.md`

## 🔐 Activation chiffrement URL (Optionnel)

Dans `bootstrap/app.php`, ajoutez:

```php
use App\Http\Middleware\EncryptUrls;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        EncryptUrls::class,
    ]);
})
```

## 📊 Panneau Admin

Accédez à: `http://votresite.com/admin/chatbot`

---

**Besoin d'aide ?** Consultez `GUIDE_CHATBOT_INSTALLATION.md` pour la documentation complète.
