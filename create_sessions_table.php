<?php
// Script pour créer la table sessions manquante

$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Créer la table sessions
$pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id INTEGER NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

echo "✅ Table 'sessions' créée avec succès !\n";
