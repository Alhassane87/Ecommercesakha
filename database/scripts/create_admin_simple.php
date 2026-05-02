<?php
// Créer l'utilisateur admin Sakha

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Supprimer l'admin existant
    $pdo->exec("DELETE FROM users WHERE email = 'sakha2228@gmail.com'");
    
    // Créer l'admin
    $pass = password_hash('Kaffo123456789', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, 'admin', NOW(), NOW())");
    $stmt->execute(['Admin Sakha', 'sakha2228@gmail.com', $pass]);
    
    echo "✅ Admin créé avec succès!\n";
    echo "Email: sakha2228@gmail.com\n";
    echo "Mot de passe: Kaffo123456789\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
