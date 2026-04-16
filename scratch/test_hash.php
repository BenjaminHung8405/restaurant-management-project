<?php
$hash = '$2a$10$8ug2rwfPD3N8K9HVSE3pnuJKKGJV5Kt.JowFQkqVpYmBSLGGzWzTK';
$password = 'admin123';
echo "Hash: " . $hash . "\n";
echo "Password: " . $password . "\n";
echo "Match: " . (password_verify($password, $hash) ? 'YES' : 'NO') . "\n";
