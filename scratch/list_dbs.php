<?php
$host = '127.0.0.1';
$user = 'root';
$pass = 'vertrigo';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $stmt = $pdo->query("SHOW DATABASES");
    $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases found: " . implode(", ", $dbs) . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
