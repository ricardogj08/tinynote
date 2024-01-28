<?php

/*
 * Registra todas las rutas y middlewares del sitio web.
 */

$app->get('/', function ($req, $res) {
    $res->send('Hello world!');
});
