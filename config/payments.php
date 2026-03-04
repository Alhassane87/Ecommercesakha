<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment configuration
    |--------------------------------------------------------------------------
    |
    | Configure provider credentials here or via .env. Supported drivers:
    | - wave
    | - orange_money
    | - card (Stripe)
    | - cash
    |
    */

    'default' => env('PAYMENT_DRIVER', env('PAYMENT_MODE', 'mock')),

    'wave' => [
        'api_url' => env('WAVE_API_URL', null),
        'api_key' => env('WAVE_API_KEY', null),
    ],

    'orange_money' => [
        'api_url' => env('ORANGE_API_URL', null),
        'api_key' => env('ORANGE_API_KEY', null),
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET', null),
        'currency' => env('STRIPE_CURRENCY', 'eur'),
        'success_url' => env('STRIPE_SUCCESS_URL', null),
        'cancel_url' => env('STRIPE_CANCEL_URL', null),
    ],

    'modes' => [
        'mock', // dev/test - payments are simulated
        'sandbox',
        'live',
    ],
];
