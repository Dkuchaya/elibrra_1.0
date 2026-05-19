<?php

// Add this inside config/database.php under the connections array:
return [
    'old_mysql' => [
        'driver' => 'mysql',
        'url' => env('OLD_DATABASE_URL'),
        'host' => env('OLD_DB_HOST', '127.0.0.1'),
        'port' => env('OLD_DB_PORT', '3306'),
        'database' => env('OLD_DB_DATABASE', 'old_elibrary'),
        'username' => env('OLD_DB_USERNAME', 'root'),
        'password' => env('OLD_DB_PASSWORD', ''),
        'unix_socket' => env('OLD_DB_SOCKET', ''),
        'charset' => env('OLD_DB_CHARSET', 'utf8mb4'),
        'collation' => env('OLD_DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
    ],
];
