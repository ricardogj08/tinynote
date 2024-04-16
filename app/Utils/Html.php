<?php

namespace App\Utils;

use Parsedown;

class Html
{
    /*
     * Escapa un texto a HTML 5.
     */
    public static function escape(?string $text = '')
    {
        return htmlentities($text, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }

    /*
     * Convierte un texto en Markdown a HTML 5.
     */
    public static function markdown(?string $text = '')
    {
        return Parsedown::instance()
            ->setSafeMode(true)
            ->setMarkupEscaped(true)
            ->setBreaksEnabled(false)
            ->setUrlsLinked(false)
            ->text($text);
    }
}
