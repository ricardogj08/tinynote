<?php

namespace App\Utils;

class Files
{
    /*
     * Elimina recursivamente un directorio.
     */
    static public function rrmdir(string $path)
    {
        $path = rtrim($path, '/');

        foreach (glob($path . '/*') as $file) {
            is_dir($file) && self::rrmdir($file) || unlink($file);
        }

        is_dir($path) && rmdir($path);
    }
}
