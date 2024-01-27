<?php

namespace App\Models;

/*
 * Modelo que representa la tabla
 * de tags de los usuarios.
 */
class TagModel extends BaseModel
{
    protected $table = 'tags';

    protected $attributes = [
        'id',
        'user_id',
        'name',
        'created_at',
        'updated_at'
    ];
}
