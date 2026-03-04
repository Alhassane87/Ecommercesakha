<?php
// Script minimal pour créer la table carts

try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec('DROP TABLE IF EXISTS carts');
    $db->exec('CREATE TABLE carts (id INTEGER PRIMARY KEY, user_id INTEGER UNIQUE, session_id TEXT, created_at TEXT, updated_at TEXT)');
    
    echo "OK";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
