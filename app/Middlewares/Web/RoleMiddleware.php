<?php

namespace App\Middlewares\Web;

use PH7\JustHttp\StatusCode;

class RoleMiddleware
{
    /*
     * Comprueba si el usuario autenticado es administrador.
     */
    public function isAdmin($req, $res)
    {
        $isAdmin = $req->app->local('userAuth')['is_admin'] ?? null;

        if ($isAdmin != true) {
            $res->sendStatus(StatusCode::FORBIDDEN);
        }
    }
}
