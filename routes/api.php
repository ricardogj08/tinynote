<?php

/*
 * Registra todas las rutas de la API.
 */

$router->get('/api', function ($req, $res) {
    $res->json(['message' => 'Hello world!']);
});

$router->post('/api/v1/auth/login', '\App\Controllers\AuthController@login');
$router->post('/api/v1/notes', '\App\Controllers\NoteController@create');
