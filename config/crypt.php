<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * del cifrador de información.
 */

return [
    'pathKeys' => Env::get('CRYPT_PATH_KEYS', '../writable/keys/')
];
