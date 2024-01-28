<?php

namespace App\Controllers\Api;

use PH7\JustHttp\StatusCode;

class ApiController
{
    /*
     * Muestra la presentación de la API.
     */
    public function index($req, $res)
    {
        $res->json([
            'message' => 'Hello world!'
        ]);
    }

    /*
     * Error 404 de la API.
     */
    public function error404($req, $res)
    {
        $res->status(StatusCode::NOT_FOUND)->json([
            'errors' => 'Endpoint not found'
        ]);
    }
}
