<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la base de datos.
 */

return [
    'default' => [
        'driver' => Env::get('DB_DEFAULT_DRIVER', 'mysql'),
        'host' => Env::get('DB_DEFAULT_HOST', 'localhost'),
        'port' => Env::get('DB_DEFAULT_PORT', 3306),
        'username' => Env::get('DB_DEFAULT_USERNAME', 'root'),
        'password' => Env::get('DB_DEFAULT_PASSWORD', 'secret'),
        'database' => Env::get('DB_DEFAULT_DATABASE', 'tinynote'),
        'charset' => Env::get('DB_DEFAULT_CHARSET', 'utf8mb4'),
        'collation' => Env::get('DB_DEFAULT_COLLATION', 'utf8mb4_general_ci')
    ]
];
