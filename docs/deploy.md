# Mailer / Queue / Webhooks — recommandations de déploiement

## Mailer
Votre `.env` actuel utilise `MAIL_MAILER=log` (utilisé pour le développement). Pour envoi réel, remplacez par un transport réel : SMTP, Postmark, Resend, Amazon SES.

Exemple SMTP :

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_smtp_user
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yourdomain.com
MAIL_FROM_NAME="Sakha"

Exemple Postmark :

MAIL_MAILER=postmark
POSTMARK_TOKEN=your_postmark_token

Exemple Resend :

MAIL_MAILER=resend
RESEND_API_KEY=your_resend_key

Après modification, testez l'envoi :

```powershell
php artisan tinker
\Illuminate\Support\Facades\Mail::raw('test', fn($m) => $m->to('you@example.com')->subject('test'));
```

## Queue
Pour monter en charge, mettez les notifications et tâches longues en queue. Nous avons marqué `OrderPlacedNotification` comme `ShouldQueue`.

1) Configurez `QUEUE_CONNECTION` dans `.env`, par exemple `database` pour un démarrage simple :

QUEUE_CONNECTION=database

2) Créez la migration pour la table `jobs` si elle n'existe pas :

```powershell
php artisan queue:table
php artisan migrate
```

3) Lancez le worker (sur le serveur prod) :

```powershell
php artisan queue:work --tries=3 --timeout=60
```

Utilisez un process manager (supervisor, systemd) pour redémarrer le worker automatiquement.

## Webhooks
- En production, vérifiez toujours la signature des webhooks (ex: `Stripe-Signature`) pour éviter les appels falsifiés.
- Exposez votre endpoint webhooks (ex: `/webhooks/stripe`) et configurez le secret de webhook chez le fournisseur.
- En local, utilisez `ngrok` pour exposer votre serveur et tester les webhooks.
- Pour WhatsApp (Meta Cloud API), utilisez `/webhooks/whatsapp` (GET verify + POST event) et suivez `docs/whatsapp-chatbot.md`.

- Stripe webhook : ajoutez la variable d'environnement suivante et installez la librairie officielle `stripe-php` :

STRIPE_WEBHOOK_SECRET=whsec_...

Installez la bibliothèque PHP officielle pour permettre la vérification stricte des signatures :

```powershell
composer require stripe/stripe-php
```

Le controller tente d'utiliser `\\Stripe\\Webhook::constructEvent()` si la classe est disponible et si `STRIPE_WEBHOOK_SECRET` est défini. Sinon il fera un fallback basique (moins sûr) — donc installez la dépendance en prod.

## Commandes utiles

- Clear caches:

```powershell
php artisan optimize:clear
```

- Rebuild config cache (après avoir ajouté secrets):

```powershell
php artisan config:cache
```

Si vous le souhaitez, je peux :
- ajouter la migration `jobs` et configurer un exemple `supervisor` unit file,
- implémenter la vérification de signature Stripe dans `WebhookController::stripe`,
- créer une table `payments` pour tracer toutes les transactions.

Dites-moi quelle(s) action(s) suivante(s) je dois exécuter : `add-jobs-migration`, `stripe-webhook-verify`, `create-payments-table`, ou autre.
