<?php
// Créer la base de données sakha dans MySQL

$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';

try {
    // Se connecter sans spécifier de base de données
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sakha CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de données 'sakha' créée avec succès\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
