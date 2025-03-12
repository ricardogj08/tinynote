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
            'data' => [
                'name' => 'tinynote API RESTful',
                'description' => 'A simple markdown note taking application with encryption support built in PHP',
                'documentation' => 'https://ricardogj08.github.io/tinynote/',
                'repository' => 'https://notabug.org/ricardogj08/tinynote/',
                'license' => 'AGPL-3.0-or-later',
                'author' => [
                    'fullname' => 'Ricardo García Jiménez',
                    'email' => 'ricardogj08@riseup.net',
                    'homepage' => 'https://ricardogj08.github.io/blog/',
                    'role' => 'Backend developer'
                ]
            ]
        ]);
    }

    /*
     * Error 404 de la API.
     */
    public function error404($req, $res)
    {
        $res->status(StatusCode::NOT_FOUND)->json([
            'error' => 'Endpoint not found'
        ]);
    }
}
