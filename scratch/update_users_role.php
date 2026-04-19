<?php
require_once __DIR__ . '/../db.php';

try {
    $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cashier', 'waiter', 'kitchen') NOT NULL DEFAULT 'waiter'";
    $pdo->exec($sql);
    echo "Database updated successfully: role column modified.\n";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
