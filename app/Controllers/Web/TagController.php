<?php

namespace App\Controllers\Web;

class TagController
{
    /*
     * Renderiza el formulario de registro de tags.
     */
    public function new($req, $res)
    {
        $res->render('tags/new', [
            'res' => $res
        ]);
    }

    /*
     * Registra el tag de un usuario.
     */
    public function create($req, $res) {}
}
