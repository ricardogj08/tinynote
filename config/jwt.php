<?php

use App\Utils\Env;

/*
 * Opciones de configuración
 * de los JSON Web Tokens (JWT).
 */

return [
    'privateKey' => Env::get('JWT_PRIVATE_KEY', '../writable/jwt/rsa-private-key.pem'),
    'publicKey' => Env::get('JWT_PUBLIC_KEY', '../writable/jwt/rsa-public-key.pem'),
];
