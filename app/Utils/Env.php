<?php

namespace App\Utils;

use gullevek\dotEnv\DotEnv;

class Env
{
    private const PATH = __DIR__ . '/../../';
    private const FILENAME = '.env';

    /*
     * Carga variables de entorno desde el archivo .env
     */
    public static function loadDotEnv()
    {
        DotEnv::readEnvFile(self::PATH, self::FILENAME);
    }

    /*
     * Obtiene el valor de una variable de entorno.
     */
    public static function get(string $varname, $default = null)
    {
        return $_ENV[$varname] ?? $default;
    }

    /*
     * Establece el valor de una variable de entorno.
     */
    public static function set(string $varname, $value = null)
    {
        $_ENV[$varname] = $value;
    }

    /*
     * Establece el valor de una variable de entorno con putenv().
     */
    public static function put(string $varname, $value = null)
    {
        putenv($varname . '=' . $value);
    }
}
