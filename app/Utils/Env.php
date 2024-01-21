<?php

namespace App\Utils;

class Env
{
    /*
     * Carga variables de entorno desde el archivo .env
     */
    public static function loadDotEnv()
    {
        \gullevek\dotEnv\DotEnv::readEnvFile(__DIR__ . '/../../');
    }

    /*
     * Obtiene el valor de una variable de entorno.
     */
    public static function get(string $varname, $default = null)
    {
        return $_ENV[$varname] ?? $dafault;
    }
}
