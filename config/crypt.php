<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * del cifrador de información.
 */

return [
    'keys-directory' => Env::get('CRYPT_KEYS_DIRECTORY', '../writable/keys/')
];
