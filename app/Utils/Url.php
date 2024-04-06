<?php

namespace App\Utils;

class Url
{
    /*
     * Contruye una URL del sitio web.
     */
    public static function build($segments = [])
    {
        if (is_string($segments)) {
            $segments = explode('/', $segments);
        }

        array_unshift($segments, rtrim(Env::get('APP_URL'), '/'));

        return implode('/', $segments);
    }
}
