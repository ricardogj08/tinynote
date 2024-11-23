<?php

namespace App\Middlewares\Web;

use App\Utils\Csrf;
use  PH7\JustHttp\StatusCode;

class CsrfMiddleware
{
    /*
     * Genera un token contra ataques CSRF para
     * los formularios de la aplicación.
     */
    public function generate($req, $res)
    {
        // Genera el token contra CSRF.
        $token = Csrf::generateToken($req->path);

        $req->session['csrf_token'] = $token;

        /*
         * Se pasa la variable $req->app->local('csrf_token')
         * dentro de los controladores y middlewares
         * con el token contra ataques CSRF.
         */
        $req->app->local('csrf_token', $token);
    }

    /*
     * Comprueba un token contra ataques CSRF de un formulario.
     */
    public function verify($req, $res)
    {
        // Token generado por la aplicación.
        $token = $req->session['csrf_token'] ?? '';

        // Elimina el token generado por la aplicación.
        unset($req->session['csrf_token']);

        // Token del formulario.
        $test = $req->body['csrf_token'] ?? '';

        // Comprueba la autenticación del token del formulario.
        if (empty($token) || empty($test) || !Csrf::verify($token, $test)) {
            $res->sendStatus(StatusCode::BAD_REQUEST);
        }
    }
}
