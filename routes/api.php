<?php

/*
 * Registra todas las rutas de la API.
 */

$app->use('/api/v1/notes', '\App\Middlewares\AuthMiddleware@before');
$app->use('/api/v1/tags', '\App\Middlewares\AuthMiddleware@before');

$app->get('/api', function ($req, $res) {
    $res->json(['message' => 'Hello world!']);
});

$app->post('/api/v1/auth/login', '\App\Controllers\AuthController@login');
$app->post('/api/v1/notes', '\App\Controllers\NoteController@create');
$app->post('/api/v1/tags', '\App\Controllers\TagController@create');
