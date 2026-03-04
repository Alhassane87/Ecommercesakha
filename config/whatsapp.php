<?php

return [
    'enabled' => env('WHATSAPP_ENABLED', false),

    'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com'),
    'api_version' => env('WHATSAPP_API_VERSION', 'v20.0'),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'app_secret' => env('WHATSAPP_APP_SECRET'),
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'sakha_webhook_verify_token'),
    'timeout' => (int) env('WHATSAPP_TIMEOUT', 20),
];
