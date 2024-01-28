<?php

/*
 * Registra todas las rutas y middlewares de la API.
 */

$app->use('/api/v1/notes', '\App\Middlewares\AuthMiddleware@before');
$app->use('/api/v1/tags', '\App\Middlewares\AuthMiddleware@before');
$app->use('/api/v1/tags/:uuid', '\App\Middlewares\AuthMiddleware@before');

$app->all('/api', '\App\Controllers\ApiController@index');
$app->all('/api/v1', '\App\Controllers\ApiController@index');

$app->post('/api/v1/tags', '\App\Controllers\TagController@create');
$app->get('/api/v1/tags', '\App\Controllers\TagController@index');
$app->delete('/api/v1/tags/:uuid', '\App\Controllers\TagController@delete');

$app->post('/api/v1/auth/login', '\App\Controllers\AuthController@login');
$app->post('/api/v1/notes', '\App\Controllers\NoteController@create');

$app->all('/api/:wildcard', '\App\Controllers\ApiController@error404');
