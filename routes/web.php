<?php

/*
 * Registra todas las rutas del sitio web.
 */

$app->get('/', function ($req, $res) {
    $res->send('Hello world!');
});
