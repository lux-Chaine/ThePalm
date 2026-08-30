<?php

// Reset database script for Palm Hotel ERP

$host = 'localhost';
$port = 3306;
$dbname = 'palm_hotel';
$username = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Dropping database '$dbname'...\n";
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    
    echo "Creating database '$dbname'...\n";
    $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "✓ Database reset successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
