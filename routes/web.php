<?php

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->get('/', '\App\Controllers\Web\PageController@index');

$app->get('/tags/new', '\App\Controllers\Web\TagController@new');
$app->post('/tags', '\App\Controllers\Web\TagController@create');

$app->get('/notes/new', '\App\Controllers\Web\NoteController@new');
$app->post('/notes', '\App\Controllers\Web\NoteController@create');

$app->all('/:wildcard', '\App\Controllers\Web\PageController@error404');
