<?php

/*
 * Registra todas las rutas de la API.
 */

$router->get('/api', function ($req, $res) {
    $res->json(['message' => 'Hello world!']);
});

$router->get('/api/v1/notes', '\App\Controllers\NoteController@create');
