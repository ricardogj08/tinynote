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

    public function notesTags()
    {
        return $this->leftJoin('notes_tags', 'tags.id = notes_tags.tag_id');
    }

    /*
     * Relaciona las notas de un tag.
     */
    public function notes()
    {
        return $this->notesTags()->leftJoin('notes', 'notes.id = notes_tags.note_id');
    }
}
