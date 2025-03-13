<?php

use App\Utils\Url;

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->use(Url::route(''), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\AuthMiddleware@redirect'
]);

$app->use(Url::route('login'), [
    '\App\Middlewares\Web\AuthMiddleware@redirect',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('login/action'), [
    '\App\Middlewares\Web\AuthMiddleware@redirect',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);

$app->use(Url::route('tags/new'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('tags/create'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('tags'), '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use(Url::route('tags/edit/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('tags/update/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('tags/delete/:uuid'), '\App\Middlewares\Web\AuthMiddleware@verify');

$app->use(Url::route('notes/new'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('notes/create'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('notes'), '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use(Url::route('notes/:uuid'), '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use(Url::route('notes/edit/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('notes/update/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('notes/delete/:uuid'), '\App\Middlewares\Web\AuthMiddleware@verify');

$app->use(Url::route('profile/edit'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('profile/update'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);

$app->use(Url::route('users/new'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('users/create'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('users'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin'
]);
$app->use(Url::route('users/edit/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin',
    '\App\Middlewares\Web\CsrfMiddleware@generate'
]);
$app->use(Url::route('users/update/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin',
    '\App\Middlewares\Web\CsrfMiddleware@verify'
]);
$app->use(Url::route('users/delete/:uuid'), [
    '\App\Middlewares\Web\AuthMiddleware@verify',
    '\App\Middlewares\Web\RoleMiddleware@isAdmin'
]);

/*
 * Registro de rutas.
 */

$app->get(Url::route(''), '\App\Controllers\Web\PageController@index');

$app->get(Url::route('login'), '\App\Controllers\Web\AuthController@loginView');
$app->post(Url::route('login/action'), '\App\Controllers\Web\AuthController@loginAction');
$app->get(Url::route('logout'), '\App\Controllers\Web\AuthController@logout');

$app->get(Url::route('tags/new'), '\App\Controllers\Web\TagController@new');
$app->post(Url::route('tags/create'), '\App\Controllers\Web\TagController@create');
$app->get(Url::route('tags'), '\App\Controllers\Web\TagController@index');
$app->get(Url::route('tags/edit/:uuid'), '\App\Controllers\Web\TagController@edit');
$app->post(Url::route('tags/update/:uuid'), '\App\Controllers\Web\TagController@update');
$app->get(Url::route('tags/delete/:uuid'), '\App\Controllers\Web\TagController@delete');

$app->get(Url::route('notes/new'), '\App\Controllers\Web\NoteController@new');
$app->post(Url::route('notes/create'), '\App\Controllers\Web\NoteController@create');
$app->get(Url::route('notes'), '\App\Controllers\Web\NoteController@index');
$app->get(Url::route('notes/:uuid'), '\App\Controllers\Web\NoteController@show');
$app->get(Url::route('notes/edit/:uuid'), '\App\Controllers\Web\NoteController@edit');
$app->post(Url::route('notes/update/:uuid'), '\App\Controllers\Web\NoteController@update');
$app->get(Url::route('notes/delete/:uuid'), '\App\Controllers\Web\NoteController@delete');

$app->get(Url::route('profile/edit'), '\App\Controllers\Web\ProfileController@edit');
$app->post(Url::route('profile/update'), '\App\Controllers\Web\ProfileController@update');

$app->get(Url::route('users/new'), '\App\Controllers\Web\UserController@new');
$app->post(Url::route('users/create'), '\App\Controllers\Web\UserController@create');
$app->get(Url::route('users'), '\App\Controllers\Web\UserController@index');
$app->get(Url::route('users/edit/:uuid'), '\App\Controllers\Web\UserController@edit');
$app->post(Url::route('users/update/:uuid'), '\App\Controllers\Web\UserController@update');
$app->get(Url::route('users/delete/:uuid'), '\App\Controllers\Web\UserController@delete');

$app->all(Url::route(':wildcard'), '\App\Controllers\Web\PageController@error404');
