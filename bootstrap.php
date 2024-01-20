<?php

require __DIR__ . '/vendor/autoload.php';

/*
 * Solución temporal si el servidor HTTP
 * no soporta reescritura de rutas.
 */
$_GET['_path_'] = $_SERVER['REQUEST_URI'] ?? '/';

/*
 * Carga variables de entorno desde el archivo .env
 */
\gullevek\dotEnv\DotEnv::readEnvFile(__DIR__);

/**
 * Define la configuración de la base de datos
 * para el ORM de la aplicación.
 */
\PhpOrm\DB::config(__DIR__ . '/config/database.php');

/*
 * Crea una instancia de la aplicación.
 */
$app = new \PhpExpress\Application();

/*
 * Crea una instancia aislada de rutas y middlewares.
 */
$router = new \PhpExpress\Router($app);

/*
 * Carga los archivos de definición de rutas.
 *
 * La variable $router se pasa en los archivos de la
 * carpeta routes para registrar rutas y middlewares.
 */
require_once __DIR__ . '/routes/api.php';
require_once __DIR__ . '/routes/web.php';

/*
 * Ejecuta la aplicación.
 */
$router->run();
