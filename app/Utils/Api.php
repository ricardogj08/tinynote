<?php

namespace App\Utils;

use WpOrg\Requests\Session;

class Api
{
    public static $headers = [];

    /*
     * Obtiene una instancia del cliente HTTP para la API.
     */
    public static function client()
    {
        $session = new Session(Url::build('api/'));

        $session->headers = array_merge($session->headers, self::$headers);

        return $session;
    }

    /*
     * Establece el token de autenticación.
     */
    public static function setAuth(?string $token = '')
    {
        self::$headers['Authorization'] = 'Bearer ' . $token;
    }
}
