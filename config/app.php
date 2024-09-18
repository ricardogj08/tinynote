<?php

use App\Utils\Env;

/*
 * Opciones de configuración de la aplicación.
 */

return [
    'name' => Env::get('APP_NAME', 'tinynote'),
    'url' => Env::get('APP_URL', 'http://localhost:8080/'),
    'proxy' => Env::get('APP_HTTP_PROXY', false)
];
