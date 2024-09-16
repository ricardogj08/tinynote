<?php

namespace App\Utils;

use App\Utils\Env;
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

        if ($proxy = Env::get('APP_PROXY')) {
            $session->options['proxy'] = $proxy;
        }

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
