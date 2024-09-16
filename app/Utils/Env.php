<?php

namespace App\Utils;

use gullevek\dotEnv\DotEnv;

class Env
{
    private const path = __DIR__ . '/../../';
    private const filename = '.env';

    /*
     * Carga variables de entorno desde el archivo .env
     */
    public static function loadDotEnv()
    {
        DotEnv::readEnvFile(self::path, self::filename);
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
        putenv($varname . '=' . $value);
    }
}
