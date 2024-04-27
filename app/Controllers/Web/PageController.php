<?php

namespace App\Controllers\Web;

use App\Utils\Env;
use App\Utils\Html;
use PH7\JustHttp\StatusCode;

class PageController
{
    /*
     * Página principal del sitio web.
     */
    public function index($req, $res)
    {
        $res->send(Html::escape(sprintf('Hello to %s!', Env::get('APP_NAME'))));
    }

    /*
     * Error 404 del sitio web.
     */
    public function error404($req, $res)
    {
        $res->sendStatus(StatusCode::NOT_FOUND);
    }
}
