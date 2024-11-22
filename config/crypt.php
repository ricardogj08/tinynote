<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * del cifrador de información.
 */

return [
    'path_keys' => Env::get('CRYPT_PATH_KEYS', '../writable/keys/')
];
