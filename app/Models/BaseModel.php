<?php

namespace App\Models;

use PhpOrm\DB;

class BaseModel extends DB
{
    public static function factory()
    {
        return new self();
    }
}
