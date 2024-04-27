<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * de los JSON Web Tokens (JWT).
 */

return [
    'privateKeyPath' => Env::get('JWT_PRIVATE_KEY_PATH', '../writable/jwt/rsa-private-key.pem'),
    'publicKeyPath' => Env::get('JWT_PUBLIC_KEY_PATH', '../writable/jwt/rsa-public-key.pem')
];
