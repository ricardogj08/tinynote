<?php

namespace App\Utils;

class Csrf
{
    private const algorithm = 'sha256';
    private const lengthBytes = 32;

    /*
     * Genera una llave de cifrado del token.
     */
    static private function generateKey()
    {
        return bin2hex(random_bytes(self::lengthBytes));
    }

    /*
     * Genera un token con la llave de cifrado.
     */
    static public function generateToken(string $data)
    {
        return hash_hmac(self::algorithm, $data, self::generateKey());
    }

    /*
     * Comprueba la autenticación de un token.
     */
    static public function verify(string $token, string $test)
    {
        return hash_equals($token, $test);
    }
}
