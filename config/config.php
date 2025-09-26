<?php
// Load .env into PHP environment (simple parser)
$__envFile = __DIR__ . '/../.env';
if (is_file($__envFile)) {
    foreach (file($__envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $__line) {
        $__line = trim($__line);
        if ($__line === '' || $__line[0] === '#') continue;
        [$__k, $__v] = array_pad(explode('=', $__line, 2), 2, '');
        $__k = trim($__k);
        $__v = trim($__v);
        $__v = trim($__v, "\"' ");
        if ($__k !== '') {
            $already = getenv($__k);
            if ($already === false || $already === '') { // do not override server-provided env vars
                putenv("$__k=$__v"); $_ENV[$__k] = $__v; $_SERVER[$__k] = $__v;
            }
        }
    }
}

// Basic configuration
return [
    'app_name' => 'Tabeebna',
    'env' => getenv('APP_ENV') ?: 'local', // local | production
    'debug' => (getenv('APP_DEBUG') === 'true'),

    // Base URL (without trailing slash). Example: http://localhost/doctorna or http://localhost:8000
    // If empty, it will be auto-detected from the request
    'base_url' => getenv('BASE_URL') ?: '',

    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int)(getenv('DB_PORT') ?: 3306),
        'database' => getenv('DB_DATABASE') ?: 'doctorna',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    // Security
    'session_name' => 'doctorna_session',
    'csrf_key' => 'doctorna_csrf_token',

    // Mail settings (simple). Set force_log=false to attempt real sending via PHP mail()
    'mail' => [
        'from_email' => getenv('MAIL_FROM_EMAIL') ?: 'no-reply@doctorna.local',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'Tabeebna',
        'force_log' => (getenv('APP_ENV') !== 'production'),
        // For future SMTP integration:
        // 'smtp' => [ 'host' => '', 'port' => 587, 'username' => '', 'password' => '', 'encryption' => 'tls' ],
    ],
];

