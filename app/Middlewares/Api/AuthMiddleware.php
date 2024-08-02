<?php

namespace App\Middlewares\Api;

use App\Models\UserModel;
use App\Utils\Jwt;
use PH7\JustHttp\StatusCode;
use UnexpectedValueException;

class AuthMiddleware
{
    /*
     * Comprueba la autenticación de un token.
     */
    public function verify($req, $res)
    {
        $header = $req->header('Authorization');

        if (empty($header)) {
            $res->status(StatusCode::UNAUTHORIZED)->json([
                'error' => 'Unauthorized endpoint',
            ]);
        }

        /*
         * Obtiene el token de autenticación
         * desde el header de la petición.
         */
        $token = explode(' ', $header ?? '');
        $token = end($token);

        try {
            // Decodifica el token de autenticación.
            $payload = (new Jwt())->decode($token);
        } catch (UnexpectedValueException $e) {
            $res->status(StatusCode::UNAUTHORIZED)->json([
                'error' => $e->getMessage()
            ]);
        }

        $payload = json_decode(json_encode($payload), true);

        // Consulta la información del usuario autenticado.
        $userAuth = UserModel::factory()
            ->select('id, active, is_admin')
            ->find($payload['sub'] ?? null);

        // Comprueba si el usuario está registrado.
        if (empty($userAuth)) {
            $res->status(StatusCode::UNAUTHORIZED)->json([
                'error' => 'Unauthorized user'
            ]);
        }

        /*
         * Se pasa la variable $req->app->local('userAuth')
         * dentro de los controladores y middlewares
         * con la información del usuario autenticado.
         */
        $req->app->local('userAuth', $userAuth);
    }
}
