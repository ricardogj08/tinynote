<?php

namespace App\Utils;

class Html
{
    /*
     * Escapa un string a HTML5.
     */
    public static function escape(?string $str = '')
    {
        return htmlentities($str, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }
}
