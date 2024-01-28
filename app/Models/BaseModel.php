<?php

namespace App\Models;

use PhpOrm\DB;

/*
 * Modelo base para todos los modelos.
 */
class BaseModel extends DB
{
    public static function factory()
    {
        return new static();
    }
}
