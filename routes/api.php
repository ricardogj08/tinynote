<?php

/*
 * Registra todas las rutas y middlewares de la API.
 */

$app->use('api/v1/auth/me', '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use('api/v1/auth/logout', '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use('api/v1/auth/refresh', '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use('api/v1/tags', '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use('api/v1/tags/:uuid', '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use('api/v1/notes', '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use('api/v1/notes/:uuid', '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use('api/v1/profile', '\App\Middlewares\Api\AuthMiddleware@verify');

$app->all('api', '\App\Controllers\Api\ApiController@index');
$app->all('api/v1', '\App\Controllers\Api\ApiController@index');

$app->post('api/v1/auth/login', '\App\Controllers\Api\AuthController@login');
$app->get('api/v1/auth/me', '\App\Controllers\Api\AuthController@me');
$app->get('api/v1/auth/logout', '\App\Controllers\Api\AuthController@logout');
$app->get('api/v1/auth/refresh', '\App\Controllers\Api\AuthController@refresh');

$app->put('api/v1/profile', '\App\Controllers\Api\ProfileController@update');

$app->post('api/v1/tags', '\App\Controllers\Api\TagController@create');
$app->get('api/v1/tags', '\App\Controllers\Api\TagController@index');
$app->get('api/v1/tags/:uuid', '\App\Controllers\Api\TagController@show');
$app->put('api/v1/tags/:uuid', '\App\Controllers\Api\TagController@update');
$app->delete('api/v1/tags/:uuid', '\App\Controllers\Api\TagController@delete');

$app->post('api/v1/notes', '\App\Controllers\Api\NoteController@create');
$app->get('api/v1/notes', '\App\Controllers\Api\NoteController@index');
$app->get('api/v1/notes/:uuid', '\App\Controllers\Api\NoteController@show');
$app->put('api/v1/notes/:uuid', '\App\Controllers\Api\NoteController@update');
$app->delete('api/v1/notes/:uuid', '\App\Controllers\Api\NoteController@delete');

$app->all('api/:wildcard', '\App\Controllers\Api\ApiController@error404');
