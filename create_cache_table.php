<?php
// Script pour créer la table cache manquante

$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Créer la table cache pour le rate limiting
$pdo->exec("CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
)");

// Créer la table cache_locks pour les verrous
$pdo->exec("CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
)");

echo "✅ Tables 'cache' et 'cache_locks' créées avec succès !\n";
