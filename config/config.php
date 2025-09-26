<?php
// Basic configuration
return [
    'app_name' => 'Tabeebna',
    'env' => 'local', // local | production
    'debug' => true,

    // Base URL (without trailing slash). Example: http://localhost/doctorna or http://localhost:8000
    // If empty, it will be auto-detected from the request
    'base_url' => '',

    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'doctorna',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    // Security
    'session_name' => 'doctorna_session',
    'csrf_key' => 'doctorna_csrf_token',

    // Mail settings (simple). Set force_log=false to attempt real sending via PHP mail()
    'mail' => [
        'from_email' => 'no-reply@doctorna.local',
        'from_name' => 'Tabeebna',
        'force_log' => true,
        // For future SMTP integration:
        // 'smtp' => [ 'host' => '', 'port' => 587, 'username' => '', 'password' => '', 'encryption' => 'tls' ],
    ],
];

