<?php

namespace App\Core;

class App
{
    protected $router;

    public function __construct()
    {
        $this->router = new Router();

        $routesFile = base_path('routes/web.php');
        if (file_exists($routesFile)) {
            $registerRoutes = require $routesFile;

            if (is_callable($registerRoutes)) {
                $registerRoutes($this->router);
            }
        }
    }

    public function run()
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        
        // Handle subdirectory installation
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);
        
        // Normalize basePath: ensure it ends with / if it's not just /
        if ($basePath !== '/' && $basePath !== '\\') {
            $basePath = rtrim($basePath, '/\\');
        } else {
            $basePath = '';
        }

        // Remove the base path from the request URI
        if ($basePath !== '' && strpos($requestUri, $basePath) === 0) {
            $path = substr($requestUri, strlen($basePath));
        } else {
            $path = $requestUri;
        }

        // Strip query string
        $path = parse_url($path, PHP_URL_PATH);

        if ($path === false || $path === null || $path === '' || $path === '/') {
            $path = '/';
        }

        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $this->router->dispatch($method, $path);
    }

    public function router()
    {
        return $this->router;
    }
}
