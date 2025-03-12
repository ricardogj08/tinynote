<?php

namespace App\Utils;

class Csrf
{
    private const ALGORITHM = 'sha256';
    private const LENGTH_BYTES = 32;

    /*
     * Genera una llave de cifrado del token.
     */
    static private function generateKey()
    {
        return bin2hex(random_bytes(self::LENGTH_BYTES));
    }

    /*
     * Genera un token con la llave de cifrado.
     */
    static public function generateToken(string $data)
    {
        return hash_hmac(self::ALGORITHM, $data, self::generateKey());
    }

    /*
     * Comprueba la autenticación de un token.
     */
    static public function verify(string $token, string $test)
    {
        return hash_equals($token, $test);
    }
}
