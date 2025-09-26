<?php
// If Composer autoload exists, include it first
$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoload)) require_once $autoload;

// Front controller

// Composer autoload (optional if added later)
// require __DIR__ . '/../vendor/autoload.php';

// Simple PSR-4 like autoloader for this project (supports when project lives inside webroot)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $candidates = [__DIR__ . '/../src/', __DIR__ . '/src/'];
    foreach ($candidates as $base_dir) {
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (is_file($file)) { require $file; return; }
    }
});

use App\Core\Router;
use App\Core\Request;
use App\Core\Response;

$config = (function(){
    $cands = [__DIR__ . '/../config/config.php', __DIR__ . '/config/config.php'];
    foreach ($cands as $p) { if (is_file($p)) { return require $p; } }
    http_response_code(500);
    echo 'Config file not found';
    exit;
})();
// Enable verbose errors if debug
if (!headers_sent()) {
    if (!empty($config['debug'])) {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
    }
}

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_name($config['session_name']);
    session_start();
}

// Load Lang and set locale
if (!class_exists('App\\Core\\Lang')) {
    $cands = [__DIR__ . '/../src/Core/Lang.php', __DIR__ . '/src/Core/Lang.php'];
    foreach ($cands as $p) { if (is_file($p)) { require_once $p; break; } }
}
\App\Core\Lang::setLocale($_SESSION['locale'] ?? 'ar');


// Bootstrap core
(function(){
    $cands = [__DIR__ . '/../src/Core/helpers.php', __DIR__ . '/src/Core/helpers.php'];
    foreach ($cands as $p) { if (is_file($p)) { require $p; return; } }
})();

$request = new Request($config);
$response = new Response();
$router = new Router($request, $response, $config);

// Web routes
$__webCands = [__DIR__ . '/../routes/web.php', __DIR__ . '/routes/web.php'];
foreach ($__webCands as $__p) { if (is_file($__p)) { require $__p; break; } }

// API routes
$__apiCands = [__DIR__ . '/../routes/api.php', __DIR__ . '/routes/api.php'];
foreach ($__apiCands as $__p) { if (is_file($__p)) { require $__p; break; } }

// Dispatch
$router->dispatch();

