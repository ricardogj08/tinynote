<?php

namespace App\Controllers;

use App\Models\NoteModel;

class NoteController
{
    /*
     * Registra la nota de un usuario.
     */
    public function create($req, $res)
    {
        $noteModel = NoteModel::factory();
    }
}
