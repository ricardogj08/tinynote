<?php

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->get('/', '\App\Controllers\Web\PageController@index');

$app->get('/notes/new', '\App\Controllers\Web\NoteController@new');

$app->all('/:wildcard', '\App\Controllers\Web\PageController@error404');
