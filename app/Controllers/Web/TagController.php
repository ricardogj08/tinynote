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
            'app' => $req->app
        ]);
    }

    /*
     * Registra el tag de un usuario.
     */
    public function create($req, $res) {}

    /*
     * Consulta los tags del usuario.
     */
    public function index($req, $res) {}
}
