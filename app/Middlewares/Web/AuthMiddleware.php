<?php

namespace App\Middlewares\Web;

use App\Utils\Api;
use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class AuthMiddleware
{
    /*
     * Comprueba la autenticación de una cookie.
     */
    public function verify($req, $res)
    {
        $userAuth = $req->cookies['userAuth'] ?? null;

        if (empty($userAuth)) {
            $res->redirect(Url::build('login'), StatusCode::FOUND);
        }

        Api::setAuth($userAuth);
    }

    /*
     * Redirecciona a una URL si existe la autenticación de la cookie.
     */
    public function redirect($req, $res)
    {
        $userAuth = $req->cookies['userAuth'] ?? null;

        if (!empty($userAuth)) {
            $req->redirect(Url::build('notes'), StatusCode::FOUND);
        }
    }
}
