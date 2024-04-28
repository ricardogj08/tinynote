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
        $token = $req->cookies['userAuth'] ?? null;

        if (empty($token)) {
            $res->redirect(Url::build('login'), StatusCode::FOUND);
        }

        Api::setAuth($token);

        $client = Api::client();

        /*
         * Realiza la petición de consulta de
         * la información del usuario autenticado.
         */
        $response = $client->get('v1/auth/me');

        $body = json_decode($response->body ?? '', true);

        $userAuth = $body['data'] ?? [];

        // Comprueba el cuerpo de la petición.
        if (empty($response->success) || empty($userAuth)) {
            // Envía el mensaje de error de la petición.
            $req->session['error'] = $body['error'] ?? 'Your session has expired';

            $res->redirect(Url::build('logout', StatusCode::FOUND));
        }

        /*
         * Se pasa la variable $req->app->local('userAuth')
         * dentro de los controladores y middlewares
         * con la información del usuario autenticado.
         */
        $req->app->local('userAuth', $userAuth);
    }

    /*
     * Redirecciona a una URL si existe la autenticación de la cookie.
     */
    public function redirect($req, $res)
    {
        $token = $req->cookies['userAuth'] ?? null;

        if (!empty($token)) {
            $res->redirect(Url::build('notes'), StatusCode::FOUND);
        }
    }
}
