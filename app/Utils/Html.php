<?php

namespace App\Utils;

use Parsedown;

class Html
{
    private const encoding = 'UTF-8';
    private const flags = ENT_QUOTES | ENT_HTML5;

    /*
     * Escapa un texto a HTML 5.
     */
    public static function escape(?string $text = '')
    {
        return htmlentities($text ?? '', self::flags, self::encoding, true);
    }

    /*
     * Escapa algunos caracteres de un texto a HTML 5.
     */
    public static function simpleEscape(?string $text = '')
    {
        return htmlspecialchars($text ?? '', self::flags, self::encoding, true);
    }

    /*
     * Convierte un texto en Markdown a HTML 5.
     */
    public static function fromMarkdown(?string $text = '')
    {
        return Parsedown::instance()
            ->setSafeMode(true)
            ->setMarkupEscaped(true)
            ->setBreaksEnabled(false)
            ->setUrlsLinked(false)
            ->text($text ?? '');
    }
}
