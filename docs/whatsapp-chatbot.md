# WhatsApp Chatbot Setup (Meta Cloud API)

Ce guide active le chatbot WhatsApp en reutilisant `ChatbotService`.

## 1) Variables `.env`

Ajoutez ces variables :

```dotenv
WHATSAPP_ENABLED=true
WHATSAPP_API_URL=https://graph.facebook.com
WHATSAPP_API_VERSION=v20.0
WHATSAPP_PHONE_NUMBER_ID=123456789012345
WHATSAPP_ACCESS_TOKEN=EAAG...
WHATSAPP_VERIFY_TOKEN=sakha_webhook_verify_token
WHATSAPP_APP_SECRET=your_meta_app_secret
WHATSAPP_TIMEOUT=20
```

Puis rechargez la config :

```powershell
php artisan optimize:clear
```

## 2) Endpoint webhook a configurer chez Meta

- Verification (GET): `/webhooks/whatsapp`
- Event webhook (POST): `/webhooks/whatsapp`

Important:
- le token saisi dans Meta doit etre exactement `WHATSAPP_VERIFY_TOKEN`.
- le webhook POST est deja exclu du CSRF dans les routes.
- si `WHATSAPP_APP_SECRET` est defini, la signature `X-Hub-Signature-256` est verifiee.

## 3) Test local

En local, exposez le serveur Laravel avec ngrok :

```powershell
php artisan serve --host=127.0.0.1 --port=8000
ngrok http 8000
```

Utilisez ensuite l'URL publique ngrok dans la console Meta.

## 4) Comportement implemente

- Reception des messages WhatsApp via webhook.
- Reponse automatique via `ChatbotService`.
- Suggestions de produits envoyees avec nom, prix et lien produit.
- Gestion des types `text`, `button`, `interactive`, et captions d'image/document.
- Deduplication basique des retries webhook (message id en metadata session).

## 5) Verification rapide

1. Ouvrir Meta webhook verify: doit retourner HTTP 200.
2. Envoyer un message WhatsApp au numero business.
3. Verifier la reponse automatique.
4. Verifier `storage/logs/laravel.log` en cas d'erreur API.
