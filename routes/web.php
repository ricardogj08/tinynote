<?php

/*
 * Registra todas las rutas del sitio web.
 */

$router->get('/', function ($req, $res) {
    $res->send('Hello world!');
});
