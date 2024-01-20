<?php

/*
 * Registra todas las rutas de la API.
 */

$router->get('/api', function ($req, $res) {
    $res->json(['message' => 'hello world']);
});
