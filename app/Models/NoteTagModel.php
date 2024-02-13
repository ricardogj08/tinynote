<?php

namespace App\Models;

/*
 * Modelo que representa la tabla de
 * relación de las notas y los tags.
 */
class NoteTagModel extends BaseModel
{
    protected $table = 'notes_tags';

    protected $attributes = [
        'id',
        'note_id',
        'tag_id',
        'created_at',
        'updated_at'
    ];

    /*
     * Relaciona los tags de una nota.
     */
    public function tags()
    {
        return $this->join('tags', 'tags.id = notes_tags.tag_id');
    }
}
