<?php

namespace App\Middlewares\Api;

use PH7\JustHttp\StatusCode;

class RoleMiddleware
{
    /*
     * Comprueba si el rol del usuario autenticado es administrador.
     */
    public function isAdmin($req, $res)
    {
        $isAdmin = $req->app->local('userAuth')['is_admin'] ?? null;

        if ($isAdmin != true) {
            $res->status(StatusCode::UNAUTHORIZED)->json([
                'error' => 'Unauthorized user, does not have administrator role'
            ]);
        }
    }
}
