<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * de los JSON Web Tokens (JWT).
 */

return [
    'private_key_path' => Env::get('JWT_PRIVATE_KEY_PATH', '../writable/jwt/rsa-private-key.pem'),
    'public_key_path' => Env::get('JWT_PUBLIC_KEY_PATH', '../writable/jwt/rsa-public-key.pem')
];
