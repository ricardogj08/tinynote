<?php

namespace App\Models;

/*
 * Modelo que representa la tabla de usuarios.
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
        'is_active',
        'created_at',
        'updated_at'
    ];
}
