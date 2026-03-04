# 🤖 GUIDE D'INSTALLATION ET CONFIGURATION - CHATBOT IA + WHATSAPP + CHIFFREMENT URL

Ce guide explique comment configurer et utiliser le système de chatbot intelligent intégré à votre plateforme Sakha.

---

## 📋 FONCTIONNALITÉS IMPLÉMENTÉES

### ✅ 1. Chatbot IA Moderne
- Support OpenAI (GPT-3.5/GPT-4) et Google Gemini
- Interface chat en temps réel avec Alpine.js
- Historique des conversations persistant
- Contexte intelligent basé sur vos produits

### ✅ 2. Intégration WhatsApp Business
- Réception et envoi de messages WhatsApp
- Webhook pour messages entrants
- Gestion des sessions WhatsApp
- Notifications de commande automatiques

### ✅ 3. Chiffrement des URLs
- Chiffrement AES-256 des URLs dans le navigateur
- Middleware transparent pour toutes les routes
- Protection contre l'inspection des URLs

---

## 🚀 INSTALLATION

### Étape 1: Exécuter les migrations

```bash
php artisan migrate
```

Cela créera les tables suivantes:
- `conversations` - Stocke toutes les conversations
- `messages` - Stocke tous les messages
- `whatsapp_sessions` - Gère les sessions WhatsApp

---

### Étape 2: Configuration des variables d'environnement

Ajoutez ces variables à votre fichier `.env`:

```env
# Chatbot Configuration
CHATBOT_ENABLED=true
CHATBOT_PROVIDER=gemini
# OU pour OpenAI: CHATBOT_PROVIDER=openai

# Pour Google Gemini (GRATUIT)
GEMINI_API_KEY=votre_clé_api_gemini

# Pour OpenAI (Payant)
OPENAI_API_KEY=votre_clé_api_openai

# WhatsApp Business API
WHATSAPP_ENABLED=true
WHATSAPP_ACCESS_TOKEN=votre_token_whatsapp
WHATSAPP_PHONE_NUMBER_ID=votre_phone_number_id
WHATSAPP_VERIFY_TOKEN=sakha_webhook_verify_token

# Chiffrement URL (utilisera APP_KEY automatiquement)
```

---

## 🔑 OBTENIR VOS CLÉS API

### Option 1: Google Gemini (RECOMMANDÉ - GRATUIT)

1. Allez sur: https://makersuite.google.com/app/apikey
2. Cliquez sur "Create API Key"
3. Copiez la clé et ajoutez-la dans `.env` comme `GEMINI_API_KEY`

**Avantages:**
- ✅ Gratuit jusqu'à 60 requêtes/minute
- ✅ Très performant
- ✅ Multilingue (français inclus)

### Option 2: OpenAI (Payant)

1. Créez un compte sur: https://platform.openai.com/
2. Allez dans "API Keys"
3. Créez une nouvelle clé API
4. Ajoutez des crédits à votre compte
5. Copiez la clé dans `.env` comme `OPENAI_API_KEY`

**Tarifs:** ~$0.002/1000 tokens (GPT-3.5-turbo)

---

## 📱 CONFIGURATION WHATSAPP BUSINESS

### Prérequis
- Compte Facebook Business
- Application Meta for Developers
- Numéro de téléphone professionnel

### Étapes de configuration

1. **Créer une application Meta**
   - Allez sur: https://developers.facebook.com/
   - Créez une nouvelle application
   - Ajoutez le produit "WhatsApp"

2. **Configurer le Webhook**
   - URL du webhook: `https://votredomaine.com/webhooks/whatsapp`
   - Verify Token: `sakha_webhook_verify_token` (ou celui défini dans .env)
   - Abonnez-vous aux événements: `messages`

3. **Obtenir les credentials**
   - **Access Token**: Dans votre app Meta > WhatsApp > API Setup
   - **Phone Number ID**: Dans la section "Phone numbers"

4. **Tester l'intégration**
   - Envoyez un message à votre numéro WhatsApp Business
   - Vérifiez les logs Laravel: `storage/logs/laravel.log`

---

## 🎨 INTÉGRATION DU WIDGET CHATBOT

### Dans vos layouts Blade

Ajoutez le widget chatbot dans votre layout principal (par exemple `resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Votre contenu -->
    @yield('content')

    <!-- Widget Chatbot -->
    @include('components.chatbot-widget')
</body>
</html>
```

Le widget apparaîtra automatiquement en bas à droite de toutes vos pages.

---

## 🔐 ACTIVATION DU CHIFFREMENT D'URL

### Enregistrer le middleware

Dans `bootstrap/app.php` ou `app/Http/Kernel.php` (selon Laravel 12), ajoutez:

```php
use App\Http\Middleware\EncryptUrls;

// Dans la section middleware
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        EncryptUrls::class,
    ]);
})
```

**Note:** Le chiffrement s'appliquera automatiquement à toutes les URLs sauf:
- Routes d'authentification (login, register, password)
- Webhooks
- Routes de debug

---

## 🎛️ PANNEAU D'ADMINISTRATION

Accédez au dashboard chatbot via:

```
https://votredomaine.com/admin/chatbot
```

### Fonctionnalités Admin:

1. **Vue d'ensemble** (`/admin/chatbot`)
   - Liste des conversations web
   - Sessions WhatsApp actives
   - Envoi manuel de messages WhatsApp

2. **Statistiques** (`/admin/chatbot/stats`)
   - Nombre total de conversations
   - Conversations actives
   - Messages totaux
   - Sessions WhatsApp

3. **Détails conversation** (`/admin/chatbot/{id}`)
   - Historique complet des messages
   - Informations utilisateur
   - Métadonnées de conversation

---

## 🧪 TESTER LE SYSTÈME

### Test du Chatbot Web

1. Ouvrez votre site dans le navigateur
2. Cliquez sur l'icône de chat en bas à droite
3. Tapez un message (exemple: "Quels sont vos produits ?")
4. L'IA devrait répondre avec la liste des produits disponibles

### Test WhatsApp

1. Envoyez un message à votre numéro WhatsApp Business
2. Le bot devrait répondre automatiquement
3. Vérifiez dans `/admin/chatbot` que la session apparaît

### Test Chiffrement URL

1. Naviguez sur votre site
2. Inspectez l'URL dans la barre du navigateur
3. Les URLs internes devraient être chiffrées (paramètre `_encrypted=...`)

---

## 📊 PERSONNALISATION

### Modifier le prompt système du chatbot

Éditez `app/Services/ChatbotService.php`, méthode `getSystemPrompt()`:

```php
protected function getSystemPrompt(): string
{
    return <<<PROMPT
Tu es un assistant virtuel pour Sakha.
[Personnalisez le comportement ici]
PROMPT;
}
```

### Personnaliser l'apparence du widget

Éditez `resources/views/components/chatbot-widget.blade.php`:
- Changez les couleurs Tailwind (`bg-blue-600`, etc.)
- Modifiez le logo et le texte
- Ajustez les dimensions

---

## 🔧 DÉPANNAGE

### Le chatbot ne répond pas

```bash
# Vérifiez les logs
tail -f storage/logs/laravel.log

# Vérifiez la configuration
php artisan config:cache
php artisan cache:clear
```

### WhatsApp webhook ne fonctionne pas

1. Vérifiez que l'URL est accessible publiquement (pas localhost)
2. Utilisez un tunnel ngrok pour tester localement:
   ```bash
   ngrok http 8000
   ```
3. Vérifiez le verify token correspond exactement

### Erreur de clé API

- Gemini: Vérifiez que la clé est active sur https://makersuite.google.com/
- OpenAI: Vérifiez que vous avez des crédits disponibles

---

## 📞 UTILISATION AVANCÉE

### Envoyer une notification WhatsApp après commande

Dans `app/Http/Controllers/CheckoutController.php`:

```php
use App\Services\WhatsAppService;

public function process(Request $request, WhatsAppService $whatsapp)
{
    // ... traitement commande ...
    
    if ($order->phone_number) {
        $whatsapp->sendOrderConfirmation($order->phone_number, [
            'order_number' => $order->tracking_number,
            'total' => $order->total,
        ]);
    }
}
```

### Mise à jour de statut commande via WhatsApp

```php
$whatsapp->sendOrderUpdate(
    $phoneNumber,
    'shipped',
    $trackingNumber
);
```

---

## 🎯 BONNES PRATIQUES

1. **Sécurité**
   - Ne commitez jamais vos clés API dans Git
   - Utilisez `.env` pour toutes les configurations sensibles
   - Activez HTTPS en production

2. **Performance**
   - Limitez l'historique de conversation à 10-20 messages
   - Utilisez une queue pour les messages WhatsApp en volume

3. **Expérience utilisateur**
   - Testez le chatbot avec de vrais scénarios client
   - Ajustez le prompt système selon les retours
   - Surveillez les conversations dans l'admin

---

## 📚 RESSOURCES

- Documentation Laravel: https://laravel.com/docs
- Google Gemini API: https://ai.google.dev/docs
- WhatsApp Business API: https://developers.facebook.com/docs/whatsapp
- Alpine.js: https://alpinejs.dev/

---

## ✅ CHECKLIST DE MISE EN PRODUCTION

- [ ] Clés API configurées dans `.env`
- [ ] Migrations exécutées
- [ ] Widget chatbot intégré dans le layout
- [ ] Webhook WhatsApp configuré et testé
- [ ] Middleware de chiffrement activé
- [ ] Logs vérifiés pour erreurs
- [ ] Tests utilisateur effectués
- [ ] HTTPS activé
- [ ] Variables d'environnement sécurisées

---

**Félicitations !** 🎉 Votre système de chatbot IA avec WhatsApp et chiffrement URL est maintenant opérationnel !

Pour toute question ou personnalisation supplémentaire, consultez le code source dans `app/Services/`.
