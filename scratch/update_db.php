<?php
$host = '127.0.0.1';
$user = 'root';
$pass = 'vertrigo';
$dbName = 'restaurant_db';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // 1. Recreate users table
    echo "Recreating users table...\n";
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $db->exec("DROP TABLE IF EXISTS users;");
    $db->exec("CREATE TABLE users (
      id CHAR(36) NOT NULL,
      username VARCHAR(100) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      full_name VARCHAR(255) NOT NULL,
      role ENUM('admin', 'cashier', 'kitchen') NOT NULL,
      status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id),
      KEY idx_username (username),
      KEY idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    $passwordHash = password_hash('admin123', PASSWORD_BCRYPT);
    $db->exec("INSERT INTO users (id, username, password, full_name, role, status) VALUES 
    ('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'admin', '$passwordHash', 'Administrator', 'admin', 'active');");
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Users table updated successfully.\n";

    // 2. Alter orders table
    echo "Altering orders table...\n";
    $stmt = $db->query("SHOW COLUMNS FROM orders");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('created_by_id', $columns)) {
        $db->exec("ALTER TABLE orders ADD COLUMN created_by_id CHAR(36) NULL AFTER table_id");
        $db->exec("ALTER TABLE orders ADD CONSTRAINT fk_orders_created_by FOREIGN KEY (created_by_id) REFERENCES users(id) ON DELETE SET NULL");
        echo "Added created_by_id to orders.\n";
    }
    
    if (!in_array('staff_name_snapshot', $columns)) {
        $db->exec("ALTER TABLE orders ADD COLUMN staff_name_snapshot VARCHAR(255) NULL AFTER created_by_id");
        echo "Added staff_name_snapshot to orders.\n";
    }

    echo "Database update completed successfully.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
