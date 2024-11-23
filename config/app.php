<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la aplicación.
 */

return [
    'environment' => Env::get('APP_ENVIRONMENT', 'production'),
    'name' => Env::get('APP_NAME', 'tinynote'),
    'url' => Env::get('APP_URL', 'http://localhost:8080/'),
    'http_proxy' => Env::get('APP_HTTP_PROXY', false)
];
