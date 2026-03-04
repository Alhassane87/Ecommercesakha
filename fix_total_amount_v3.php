<?php
// Fix total_amount column - drop and recreate with proper default

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sakha;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'total_amount'");
    if ($result->rowCount() > 0) {
        // Drop the column
        $pdo->exec("ALTER TABLE orders DROP COLUMN total_amount");
        echo "✅ Dropped existing total_amount column\n";
    }
    
    // Add column with NULL and default 0
    $pdo->exec("ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10, 2) NULL DEFAULT 0");
    echo "✅ Added total_amount column (NULL DEFAULT 0)\n";
    
    echo "\n🎉 Column fixed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
