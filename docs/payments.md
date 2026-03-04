# Paiements — configuration et tests

Ce fichier décrit comment activer et tester les systèmes de paiement dans l'application SAKHA (dev & sandbox).

1) Modes disponibles

- mock (dev) : par défaut, tous les drivers retournent `paid` ou `pending` sans appeler d'API externe. Utile pour développement local.
- sandbox / live : si vous fournissez des clés/API pour Wave, Orange ou Stripe, les drivers tenteront d'appeler leurs endpoints. Le code supporte la redirection vers une page hébergée par le fournisseur (ex: Stripe Checkout).

2) Variables d'environnement à ajouter dans `.env`

# Stripe (Checkout API)
STRIPE_SECRET=sk_test_...   # clé secrète de test
STRIPE_CURRENCY=eur
# (optionnel) fallback si vous voulez hardcoder success/cancel
STRIPE_SUCCESS_URL=
STRIPE_CANCEL_URL=

# Wave (exemple) - variables propres à votre intégration
WAVE_API_URL=https://api.wave.example/charge
WAVE_API_KEY=your_wave_key

# Orange Money (exemple)
ORANGE_API_URL=https://api.orange.example/payment
ORANGE_API_KEY=your_orange_key

# Mode par défaut
PAYMENT_MODE=mock

3) Comportement implémenté

- `CardDriver` : si `STRIPE_SECRET` est présent et que `success_url`/`cancel_url` sont fournis, le driver crée une session Stripe Checkout via l'API et renvoie `redirect_url` (l'URL de session). L'utilisateur est redirigé automatiquement.
  - Sinon, le driver tombe en mode fallback et marque la commande `paid` (pratique pour dev local sans clé).

- `WaveDriver` et `OrangeMoneyDriver` : si les variables `API_URL` et `API_KEY` sont définies, le driver postera vers l'API. Si l'API retourne une `checkout_url` ou `payment_url`, celle-ci sera renvoyée dans `redirect_url` et le frontend redirigera l'utilisateur.
  - Sinon, fallback local marque la commande `paid`.

- `CashDriver` : marque la commande `pending` (paiement à la livraison). L'administrateur devra confirmer manuellement.

4) Points de sécurité / production

- Webhooks : configurez vos webhooks côté fournisseur (Stripe, Wave, Orange) vers `/webhooks/stripe` ou `/webhooks/{provider}`. En production, implémentez la vérification de signature (Stripe-Signature etc.) dans `WebhookController`.

- Ne stockez jamais vos clefs en clair dans le repo. Utilisez les variables d'environnement et un gestionnaire de secrets pour prod.

- Passez les notifications emails en queue (implémentez `ShouldQueue`) et démarrez un worker `php artisan queue:work` en prod.

5) Test rapide en local (mode mock)

- Assurez-vous que `.env` contient `PAYMENT_MODE=mock` (ou ne définit pas les clefs Stripe/Wave/Orange)
- Démarrez le serveur :

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

- Allez au checkout, sélectionnez `card` ou `wave` ; si aucun clé n'est configurée, la commande sera marquée `paid` immédiatement et vous serez redirigé vers la page de la commande.

6) Tester Stripe Checkout (sandbox)

- Ajoutez `STRIPE_SECRET` à `.env` (clé de test) et laissez `PAYMENT_MODE` à `sandbox` ou `live` selon vos tests.
- Assurez-vous que `APP_URL` est correct (ex: `http://127.0.0.1:8000`). Le `success_url` et `cancel_url` sont fournis dynamiquement par le contrôleur de checkout.
- Lancez le serveur et effectuez un checkout avec `card` : vous devriez être redirigé vers la page Stripe Checkout.

7) Débogage webhooks en local

- Utilisez `ngrok` pour exposer votre serveur local :

```powershell
ngrok http 8000
```

- Configurez l'URL publique retournée par ngrok comme endpoint pour webhooks dans le dashboard du fournisseur et testez.

8) Étapes suivantes recommandées

- Implémenter la vérification des signatures pour chaque fournisseur dans `WebhookController`.
- Ajouter une table `payments` dédiée si vous devez stocker histoire complète des tentatives / remboursements / fees.
- Mettre les notifications dans la queue et exécuter `php artisan queue:work` en prod.
- Ajouter des tests automatiques couvrant PaymentManager et les drivers mock.

Si vous voulez, je peux :
- ajouter une page de paiement intermédiaire (si vous préférez intégrer Stripe Elements au lieu de Checkout),
- créer la table `payments` et migrer la logique pour enregistrer trace des transactions,
- automatiser l'envoi de webhooks simulés pour tests locaux.

