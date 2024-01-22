<?php

namespace App\Models;

/*
 * Modelo que representa la tabla
 * de notas de los usuarios.
 */
class NoteModel extends BaseModel
{
    protected $table = 'notes';

    protected $attributes = [
        'id',
        'user_id',
        'title',
        'body',
        'created_at',
        'updated_at'
    ];
}
