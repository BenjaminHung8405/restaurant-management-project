<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require APP_PATH . '/Helpers/functions.php';
loadEnv(BASE_PATH . '/.env');

// Mock APP_PATH for Database class
if (!defined('APP_PATH')) define('APP_PATH', BASE_PATH . '/app');

require APP_PATH . '/Core/Database.php';

use App\Core\Database;

header('Content-Type: text/plain');

try {
    echo "Connecting to database...\n";
    $db = Database::connection();
    echo "Connected successfully.\n\n";

    echo "Checking tables...\n";
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n\n";

    if (in_array('users', $tables)) {
        echo "Checking users table...\n";
        $stmt = $db->query("SELECT id, email, full_name, role, password_hash FROM users");
        $users = $stmt->fetchAll();
        
        if (empty($users)) {
            echo "Users table is EMPTY!\n";
            echo "Re-seeding admin user...\n";
            $id = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
            $email = 'admin@restaurant.com';
            $password = 'admin123';
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $fullName = 'Admin User';
            
            $stmt = $db->prepare("INSERT INTO users (id, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, 'admin')");
            $stmt->execute([$id, $email, $hash, $fullName]);
            echo "Admin user created successfully with password: $password\n";
        } else {
            foreach ($users as $user) {
                echo "ID: {$user['id']} | Email: {$user['email']} | Role: {$user['role']} | Name: {$user['full_name']}\n";
                // Verify password_verify is working in this environment
                $testPass = 'admin123';
                $match = password_verify($testPass, $user['password_hash']);
                echo "  -> Password 'admin123' match: " . ($match ? "YES" : "NO") . "\n";
            }
        }
    } else {
        echo "Users table does NOT exist!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
