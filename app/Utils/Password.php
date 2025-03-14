<?php

namespace App\Utils;

class Password
{
    private const ALGORITHM = PASSWORD_DEFAULT;

    /*
     * Encripta una contraseña.
     */
    public static function encrypt(string $password)
    {
        return password_hash($password, self::ALGORITHM);
    }

    /*
     * Comprueba que la contraseña coincida con el hash.
     */
    public static function verify(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}
