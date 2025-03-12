<?php

namespace App\Utils;

use RuntimeException;

class Config
{
    private const PATH = __DIR__ . '/../../config/';

    /*
     * Obtiene opciones de configuración desde un archivo.
     */
    public static function getFromFilename(string $filename)
    {
        $config = require self::PATH . $filename . '.php';

        if (!is_array($config)) {
            throw new RuntimeException(sprintf('Config file options "%s" are not an array.', $filename));
        }

        return $config;
    }
}
