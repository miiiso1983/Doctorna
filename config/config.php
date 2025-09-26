<?php
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

