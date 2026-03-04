<?php
// Script pour configurer MySQL dans .env

$envFile = __DIR__ . '/.env';
$content = file_get_contents($envFile);

// Remplacer les paramètres SQLite par MySQL
$content = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $content);
$content = preg_replace('/DB_HOST=.*/', 'DB_HOST=127.0.0.1', $content);
$content = preg_replace('/DB_PORT=.*/', 'DB_PORT=3306', $content);
$content = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=sakha', $content);
$content = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=root', $content);
$content = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=', $content);

file_put_contents($envFile, $content);

echo "✅ Configuration MySQL appliquée\n";
echo "DB_CONNECTION=mysql\n";
echo "DB_DATABASE=sakha\n";
