<?php

namespace App\Utils;

class Env
{
    private static $path = __DIR__ . '/../../';
    private static $filename = '.env';

    /*
     * Carga variables de entorno desde el archivo .env
     */
    public static function loadDotEnv()
    {
        \gullevek\dotEnv\DotEnv::readEnvFile(self::$path, self::$filename);
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
}
