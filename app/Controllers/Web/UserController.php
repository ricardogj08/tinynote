<?php

namespace App\Controllers\Web;

class UserController
{
    /*
     * Renderiza el formulario de registro de usuarios.
     */
    public function new($req, $res)
    {
        $res->render('users/new', [
            'app' => $req->app,
        ]);
    }

    /*
     * Registra un nuevo usuario.
     */
    public function create($req, $res) {}

    /*
     * Consulta los usuarios registrados.
     */
    public function index($req, $res)
    {
        $res->render('users/index', [
            'app' => $req->app,
        ]);
    }

    /*
     * Renderiza el formulario de modificación de usuarios.
     */
    public function edit($req, $res)
    {
        $res->render('users/edit', [
            'app' => $req->app,
        ]);
    }

    /*
     * Modifica la información de un usuario.
     */
    public function update($req, $res) {}

    /*
     * Elimina un usuario.
     */
    public function delete($req, $res) {}
}
