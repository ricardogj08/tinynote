<?php

namespace App\Models;

/*
 * Modelo que representa la tabla de los usuarios.
 */
class UserModel extends BaseModel
{
    protected $table = 'users';

    protected $attributes = [
        'id',
        'username',
        'email',
        'password',
        'active',
        'is_admin',
        'created_at',
        'updated_at'
    ];

    /*
     * Relaciona los tags con sus usuarios.
     */
    public function tags()
    {
        return $this->leftJoin('tags', 'users.id = tags.user_id');
    }

    /*
     * Relaciona las notas con sus usuarios.
     */
    public function notes()
    {
        return $this->leftJoin('notes', 'users.id = notes.user_id');
    }
}
