<?php

namespace App\Controllers\Web;

use PH7\JustHttp\StatusCode;

class PageController
{
    /*
     * Página principal del sitio web.
     */
    public function index($req, $res)
    {
        $res->send('Hello world!');
    }

    /*
     * Error 404 del sitio web.
     */
    public function error404($req, $res)
    {
        $res->sendStatus(StatusCode::NOT_FOUND);
    }
}
