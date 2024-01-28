<?php

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->get('/', '\App\Controllers\Web\PageController@index');

$app->all('/:wildcard', '\App\Controllers\Web\PageController@error404');
