<?php

namespace App\Utils;

use Exception;

class Config
{
    private static $path = __DIR__ . '/../../config/';

    /*
     * Obtiene opciones de configuración desde un archivo.
     */
    public static function getFromFile(string $filename)
    {
        $config = require self::$path . $filename . '.php';

        if (!is_array($config)) {
            throw new Exception("The {$filename} config file options are not an array.");
        }

        return $config;
    }
}
