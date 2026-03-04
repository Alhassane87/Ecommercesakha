<?php

// Script de configuration Google OAuth sans secret en dur.
// Usage:
//   $env:GOOGLE_CLIENT_ID="..."
//   $env:GOOGLE_CLIENT_SECRET="..."
//   php setup_google.php

$clientId = getenv('GOOGLE_CLIENT_ID') ?: 'your_google_client_id';
$clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: 'your_google_client_secret';
$redirectUri = getenv('GOOGLE_REDIRECT_URI') ?: 'http://127.0.0.1:8000/auth/google/callback';

$targetEnv = '.env';
$baseContent = file_exists($targetEnv)
    ? (string) file_get_contents($targetEnv)
    : (string) file_get_contents('.env.example');

// Nettoyer anciennes valeurs OAuth pour eviter les doublons.
$baseContent = preg_replace('/^GOOGLE_CLIENT_ID=.*$/m', '', $baseContent);
$baseContent = preg_replace('/^GOOGLE_CLIENT_SECRET=.*$/m', '', $baseContent);
$baseContent = preg_replace('/^GOOGLE_REDIRECT_URI=.*$/m', '', $baseContent);
$baseContent = rtrim((string) $baseContent) . "\n\n";

$baseContent .= "GOOGLE_CLIENT_ID={$clientId}\n";
$baseContent .= "GOOGLE_CLIENT_SECRET={$clientSecret}\n";
$baseContent .= "GOOGLE_REDIRECT_URI={$redirectUri}\n";

file_put_contents($targetEnv, $baseContent);

echo "Fichier .env configure.\n";
echo "GOOGLE_CLIENT_ID et GOOGLE_REDIRECT_URI enregistres.\n";
if ($clientSecret === 'your_google_client_secret') {
    echo "ATTENTION: defini GOOGLE_CLIENT_SECRET avant production.\n";
}
