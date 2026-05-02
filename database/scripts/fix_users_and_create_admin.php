<?php
// Vérifier et corriger la structure de la table users

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la colonne role existe
    $result = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($result->rowCount() == 0) {
        // Ajouter la colonne role
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'user' AFTER google_refresh_token");
        echo "✅ Colonne 'role' ajoutée à la table users\n";
    } else {
        echo "ℹ️ Colonne 'role' existe déjà\n";
    }
    
    // Créer l'admin
    $pdo->exec("DELETE FROM users WHERE email = 'sakha2228@gmail.com'");
    $pass = password_hash('Kaffo123456789', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, 'admin', NOW(), NOW())");
    $stmt->execute(['Admin Sakha', 'sakha2228@gmail.com', $pass]);
    
    echo "✅ Admin créé avec succès!\n";
    echo "📧 Email: sakha2228@gmail.com\n";
    echo "🔑 Mot de passe: Kaffo123456789\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
