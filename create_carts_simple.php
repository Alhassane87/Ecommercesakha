<?php
$db = new PDO('sqlite:database/database.sqlite');
$db->exec('CREATE TABLE IF NOT EXISTS carts (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, session_id TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)');
echo "Table carts creee";
