<?php

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->use('login', '\App\Middlewares\Web\AuthMiddleware@redirect');
$app->use('logout', '\App\Middlewares\Web\AuthMiddleware@verify');

$app->use('tags/new', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('tags/create', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('tags', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('tags/edit/:uuid', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('tags/update/:uuid', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('tags/delete/:uuid', '\App\Middlewares\Web\AuthMiddleware@verify');

$app->use('notes/new', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('notes/create', '\App\Middlewares\Web\AuthMiddleware@verify');
$app->use('notes', '\App\Middlewares\Web\AuthMiddleware@verify');

$app->get('', '\App\Controllers\Web\PageController@index');

$app->get('login', '\App\Controllers\Web\AuthController@loginView');
$app->post('login', '\App\Controllers\Web\AuthController@loginAction');
$app->get('logout', '\App\Controllers\Web\AuthController@logout');

$app->get('tags/new', '\App\Controllers\Web\TagController@new');
$app->post('tags/create', '\App\Controllers\Web\TagController@create');
$app->get('tags', '\App\Controllers\Web\TagController@index');
$app->get('tags/edit/:uuid', '\App\Controllers\Web\TagController@edit');
$app->post('tags/update/:uuid', '\App\Controllers\Web\TagController@update');
$app->get('tags/delete/:uuid', '\App\Controllers\Web\TagController@delete');

$app->get('notes/new', '\App\Controllers\Web\NoteController@new');
$app->post('notes/create', '\App\Controllers\Web\NoteController@create');
$app->get('notes', '\App\Controllers\Web\NoteController@index');

$app->all(':wildcard', '\App\Controllers\Web\PageController@error404');
