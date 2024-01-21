<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la base de datos.
 */

return [
    'default' => [
        'driver' => Env::get('DB_DRIVER', 'mysql'),
        'host' => Env::get('DB_HOST', 'localhost'),
        'port' => Env::get('DB_PORT', 3306),
        'username' => Env::get('DB_USERNAME', 'root'),
        'password' => Env::get('DB_PASSWORD', 'secret'),
        'database' => Env::get('DB_DATABASE', 'tinynote'),
        'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
        'collation' => Env::get('DB_COLLATION', 'utf8mb4_general_ci'),
    ],
];
