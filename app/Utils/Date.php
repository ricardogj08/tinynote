<?php

namespace App\Utils;

use DateTimeImmutable;

class Date
{
    /*
     * Humaniza un fecha.
     */
    public static function humanize(?string $datetime = 'now')
    {
        $date = new DateTimeImmutable($datetime);

        return $date->format('D, d M Y h:i:s A');
    }
}
