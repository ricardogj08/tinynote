<?php

namespace App\Middlewares\Web;

use App\Utils\Url;
use PH7\JustHttp\StatusCode;

class AuthMiddleware
{
    private $cookieName = 'userAuth';

    /*
     * Comprueba la autenticación de una cookie.
     */
    public function verify($req, $res)
    {
        $userAuth = $req->cookies[$this->cookieName];

        if (empty($userAuth)) {
            $res->redirect(Url::build('login'), StatusCode::FOUND);
        }
    }

    /*
     * Redirecciona a una URL si existe la autenticación de la cookie.
     */
    public function redirect($req, $res)
    {
        $userAuth = $req->cookies[$this->cookieName];

        if (!empty($userAuth)) {
            $req->redirect(Url::build('notes'), StatusCode::FOUND);
        }
    }
}
