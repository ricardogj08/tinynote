<?php

namespace App\Controllers\Web;

class NoteController
{
    /*
     * Renderiza el formulario de registro de notas.
     */
    public function new($req, $res)
    {
        $res->render('notes/new', [
            'res' => $res
        ]);
    }

    /*
     * Registra la nota de un usuario.
     */
    public function create($req, $res) {}
}
