<?php

use App\Utils\Url;

/*
 * Registra todas las rutas y middlewares de la API.
 */

$app->use(Url::route('api/v1/auth/me'), '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use(Url::route('api/v1/auth/logout'), '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use(Url::route('api/v1/auth/refresh'), '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use(Url::route('api/v1/tags'), '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use(Url::route('api/v1/tags/:uuid'), '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use(Url::route('api/v1/notes'), '\App\Middlewares\Api\AuthMiddleware@verify');
$app->use(Url::route('api/v1/notes/:uuid'), '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use(Url::route('api/v1/profile'), '\App\Middlewares\Api\AuthMiddleware@verify');

$app->use(Url::route('api/v1/users'), [
    '\App\Middlewares\Api\AuthMiddleware@verify',
    '\App\Middlewares\Api\RoleMiddleware@isAdmin'
]);
$app->use(Url::route('api/v1/users/:uuid'), [
    '\App\Middlewares\Api\AuthMiddleware@verify',
    '\App\Middlewares\Api\RoleMiddleware@isAdmin'
]);

/*
 * Registro de rutas.
 */

$app->all(Url::route('api'), '\App\Controllers\Api\ApiController@index');
$app->all(Url::route('api/v1'), '\App\Controllers\Api\ApiController@index');

$app->post(Url::route('api/v1/auth/login'), '\App\Controllers\Api\AuthController@login');
$app->get(Url::route('api/v1/auth/me'), '\App\Controllers\Api\AuthController@me');
$app->get(Url::route('api/v1/auth/logout'), '\App\Controllers\Api\AuthController@logout');
$app->get(Url::route('api/v1/auth/refresh'), '\App\Controllers\Api\AuthController@refresh');

$app->put(Url::route('api/v1/profile'), '\App\Controllers\Api\ProfileController@update');

$app->post(Url::route('api/v1/tags'), '\App\Controllers\Api\TagController@create');
$app->get(Url::route('api/v1/tags'), '\App\Controllers\Api\TagController@index');
$app->get(Url::route('api/v1/tags/:uuid'), '\App\Controllers\Api\TagController@show');
$app->put(Url::route('api/v1/tags/:uuid'), '\App\Controllers\Api\TagController@update');
$app->delete(Url::route('api/v1/tags/:uuid'), '\App\Controllers\Api\TagController@delete');

$app->post(Url::route('api/v1/notes'), '\App\Controllers\Api\NoteController@create');
$app->get(Url::route('api/v1/notes'), '\App\Controllers\Api\NoteController@index');
$app->get(Url::route('api/v1/notes/:uuid'), '\App\Controllers\Api\NoteController@show');
$app->put(Url::route('api/v1/notes/:uuid'), '\App\Controllers\Api\NoteController@update');
$app->delete(Url::route('api/v1/notes/:uuid'), '\App\Controllers\Api\NoteController@delete');

$app->post(Url::route('api/v1/users'), '\App\Controllers\Api\UserController@create');
$app->get(Url::route('api/v1/users'), '\App\Controllers\Api\UserController@index');
$app->get(Url::route('api/v1/users/:uuid'), '\App\Controllers\Api\UserController@show');
$app->put(Url::route('api/v1/users/:uuid'), '\App\Controllers\Api\UserController@update');
$app->delete(Url::route('api/v1/users/:uuid'), '\App\Controllers\Api\UserController@delete');

$app->all(Url::route('api/:wildcard'), '\App\Controllers\Api\ApiController@error404');
