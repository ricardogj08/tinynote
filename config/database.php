<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la base de datos.
 */

return [
    'default' => [
        'driver' => Env::get('DEFAULT_DRIVER', 'mysql'),
        'host' => Env::get('DEFAULT_HOST', 'localhost'),
        'port' => Env::get('DEFAULT_PORT', 3306),
        'username' => Env::get('DEFAULT_USERNAME', 'root'),
        'password' => Env::get('DEFAULT_PASSWORD', 'secret'),
        'database' => Env::get('DEFAULT_DATABASE', 'tinynote'),
        'charset' => Env::get('DEFAULT_CHARSET', 'utf8mb4'),
        'collation' => Env::get('DEFAULT_COLLATION', 'utf8mb4_general_ci')
    ]
];
