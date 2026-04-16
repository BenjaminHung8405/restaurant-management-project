<?php

require __DIR__ . '/bootstrap/app.php';

use App\Models\User;

try {
    $userModel = new User();
    $identity = 'admin@restaurant.com';
    $password = 'admin123';
    
    $user = $userModel->findByIdentity($identity);
    
    if ($user) {
        echo "User found: " . $user['email'] . "\n";
        echo "Stored Hash: " . $user['password_hash'] . "\n";
        
        $isValid = password_verify($password, $user['password_hash']);
        echo "Password Match (password_verify): " . ($isValid ? "YES" : "NO") . "\n";
        
        $isLegacyValid = hash_equals($user['password_hash'], $password);
        echo "Password Match (plain-text): " . ($isLegacyValid ? "YES" : "NO") . "\n";
    } else {
        echo "User NOT found: $identity\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
