<?php
// If Composer autoload exists, include it first
$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) require_once $autoload;

// Front controller

// Composer autoload (optional if added later)
// require __DIR__ . '/../vendor/autoload.php';

// Simple PSR-4 like autoloader for this project
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

$config = require __DIR__ . '/../config/config.php';

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_name($config['session_name']);
    session_start();
}

// Load Lang and set locale
if (!class_exists('App\\Core\\Lang') && is_file(__DIR__ . '/../src/Core/Lang.php')) require_once __DIR__ . '/../src/Core/Lang.php';
\App\Core\Lang::setLocale($_SESSION['locale'] ?? 'ar');


// Bootstrap core
require __DIR__ . '/../src/Core/helpers.php';

$request = new Request($config);
$response = new Response();
$router = new Router($request, $response, $config);

// Web routes
require __DIR__ . '/../routes/web.php';

// API routes
require __DIR__ . '/../routes/api.php';

// Dispatch
$router->dispatch();

