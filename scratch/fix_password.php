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

    echo "Updating admin password with fresh hash...\n";
    $email = 'admin@restaurant.com';
    $password = 'admin123';
    $freshHash = password_hash($password, PASSWORD_BCRYPT);
    
    echo "Freshly generated hash for 'admin123': $freshHash\n";
    
    $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$freshHash, $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Successfully updated password for $email\n";
    } else {
        echo "No rows updated. User might not exist or hash was identical.\n";
    }

    // Verify it now
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user) {
        $match = password_verify($password, $user['password_hash']);
        echo "Verification check after update: " . ($match ? "SUCCESS" : "FAILED") . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
