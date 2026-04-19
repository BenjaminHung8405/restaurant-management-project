<?php
define('APP_PATH', dirname(__DIR__));
require_once 'app/Core/Database.php';

function env($key, $default = null) {
    return $default; // Simplified for this script since we saw default values in database.php
}

use App\Core\Database;

try {
    $db = Database::connection();
    $result = $db->query("SHOW COLUMNS FROM tables LIKE 'updated_at'")->fetch();
    if ($result) {
        echo "COL_EXISTS";
    } else {
        echo "COL_MISSING";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
