<?php
// Script pour recréer la table sessions sans contrainte de clé étrangère problématique

$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Supprimer la table sessions si elle existe
$pdo->exec("DROP TABLE IF EXISTS sessions");

// Créer la table sessions sans contrainte de clé étrangère explicite sur users
$pdo->exec("CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INTEGER NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
)");

echo "✅ Table 'sessions' recréée avec succès !\n";
