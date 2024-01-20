<?php

/*
 * Opciones de configuración de la base de datos.
 */

return array(
    'default' => array(
        'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host'      => $_ENV['DB_HOST'] ?? 'localhost',
        'port'      => $_ENV['DB_PORT'] ?? 3306,
        'username'  => $_ENV['DB_USERNAME'] ?? 'root',
        'password'  => $_ENV['DB_PASSWORD'] ?? 'secret',
        'database'  => $_ENV['DB_DATABASE'] ?? 'tinynote',
        'charset'   => $_ENV['DB_CHARSET'] ??'utf8mb4',
        'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_general_ci',
    ),
);
