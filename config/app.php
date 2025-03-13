<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la aplicación.
 */

// Establece todas las funciones de fecha de PHP a UTC.
date_default_timezone_set('UTC');

return [
    'environment' => Env::get('APP_ENVIRONMENT', 'production'),
    'name' => Env::get('APP_NAME', 'tinynote'),
    'url' => Env::get('APP_URL', 'http://localhost:8080/'),
    'base_url' => Env::get('APP_BASE_URL', '/'),
    'http_proxy' => Env::get('APP_HTTP_PROXY', false)
];
