<?php

/*
 * Registra todas las rutas y middlewares de la API.
 */

$app->use('/api/v1/notes', '\App\Middlewares\Api\AuthMiddleware@before');
$app->use('/api/v1/tags', '\App\Middlewares\Api\AuthMiddleware@before');
$app->use('/api/v1/tags/:uuid', '\App\Middlewares\Api\AuthMiddleware@before');

$app->all('/api', '\App\Controllers\Api\ApiController@index');
$app->all('/api/v1', '\App\Controllers\Api\ApiController@index');

$app->post('/api/v1/auth/login', '\App\Controllers\Api\AuthController@login');

$app->post('/api/v1/tags', '\App\Controllers\Api\TagController@create');
$app->get('/api/v1/tags', '\App\Controllers\Api\TagController@index');
$app->delete('/api/v1/tags/:uuid', '\App\Controllers\Api\TagController@delete');

$app->post('/api/v1/notes', '\App\Controllers\Api\NoteController@create');
$app->get('/api/v1/notes', '\App\Controllers\Api\NoteController@index');

$app->all('/api/:wildcard', '\App\Controllers\Api\ApiController@error404');
