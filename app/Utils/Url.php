<?php

namespace App\Utils;

class Url
{
    /*
     * Construye una URL del sitio web.
     */
    public static function build($segments = [])
    {
        if (is_string($segments)) {
            $segments = explode('/', $segments);
        }

        array_unshift($segments, rtrim(Env::get('APP_URL'), '/'));

        return implode('/', $segments);
    }

    /*
     * Construye una URL desde la base.
     */
    public static function base($segments = [])
    {
        return preg_replace('/index\.php\/?/', '', self::build($segments));
    }
}
