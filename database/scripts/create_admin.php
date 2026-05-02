<?php
// Créer l'utilisateur admin

$host = '127.0.0.1';
$port = '3306';
$database = 'sakha';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Supprimer l'admin existant s'il y en a un
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute(['sakha2228@gmail.com']);
    
    // Créer le nouvel admin
    $adminPassword = password_hash('Kaffo123456789', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, 'admin', NOW(), NOW(), NOW())");
    $stmt->execute(['Admin Sakha', 'sakha2228@gmail.com', $adminPassword]);
    
    echo "✅ Admin créé avec succès !\n";
    echo "📧 Email: sakha2228@gmail.com\n";
    echo "🔑 Mot de passe: Kaffo123456789\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
