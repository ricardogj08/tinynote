<?php

namespace App\Utils;

use Exception;

class Config
{
    private const path = __DIR__ . '/../../config/';

    /*
     * Obtiene opciones de configuración desde un archivo.
     */
    public static function getFromFilename(string $filename)
    {
        $config = require self::path . $filename . '.php';

        if (!is_array($config)) {
            throw new Exception(sprintf('Config file options "%s" are not an array.', $filename));
        }

        return $config;
    }
}
