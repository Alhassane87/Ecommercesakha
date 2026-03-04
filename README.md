<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## SAKHA - Configuration supplémentaire et paiements

Ce dépôt contient un squelette e‑commerce (produits, panier, checkout) avec des drivers de paiement qui peuvent utiliser de vraies API si les variables d'environnement sont configurées. Pour activer les intégrations réelles suivez ces points :

1. Installer les dépendances PHP recommandées selon le provider :

- Stripe (paiement par carte) :
	- composer require stripe/stripe-php
	- ajouter STRIPE_SECRET dans `.env`

- Pour Wave et Orange Money : le code actuel utilise des appels HTTP simples via `Http::post()`.
	- configurez `WAVE_API_URL` et `WAVE_API_KEY` pour Wave
	- configurez `ORANGE_API_URL` et `ORANGE_API_KEY` pour Orange Money

2. Variables d'environnement utiles (exemples à ajouter dans `.env`):

STRIPE_SECRET=sk_test_...
STRIPE_CURRENCY=eur
WAVE_API_URL=https://api.wave.com/...
WAVE_API_KEY=your_wave_key
ORANGE_API_URL=https://api.orange.com/...
ORANGE_API_KEY=your_orange_key

3. Webhooks

Les routes de webhooks exposées sont :
- POST /webhooks/stripe
- POST /webhooks/{provider}

En production, vérifiez toujours les signatures (Stripe fournit un header `Stripe-Signature`) et validez la source du webhook.

4. Rôles et accès admin

Une migration a été ajoutée pour créer la colonne `role` sur `users` (valeur par défaut `customer`). Un middleware `EnsureUserIsAdmin` protège les routes sous `/admin`. Pour rendre un utilisateur admin, définissez `role` = `admin` en base.

5. Commandes utiles pour tester localement

```powershell
composer install
copy .env.example .env ; php artisan key:generate
php artisan migrate
npm install
npm run dev
```

6. Note sur sécurité

Les drivers fournis contiennent des implémentations de secours (simulées) si les variables d'environnement ne sont pas fournies. Avant de lancer en production, remplacez/complétez ces drivers pour respecter les bonnes pratiques du provider (signature, vérification, retry, idempotence).

