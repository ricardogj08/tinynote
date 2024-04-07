<?php

namespace App\Controllers\Web;

class AuthController
{
    /*
     * Renderiza el formulario de inicio de sesión.
     */
    public function loginView($req, $res)
    {
        $res->render('auth/login', [
            'app' => $req->app
        ]);
    }

    /*
     * Inicia la sesión de un usuario.
     */
    public function loginAction($req, $res) {}
}
