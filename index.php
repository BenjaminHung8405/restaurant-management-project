<?php

/**
 * Entry point for the application.
 * Redirects or bootstraps the MVC application.
 */

require __DIR__ . '/bootstrap/app.php';

$app = new App\Core\App();
$app->run();
