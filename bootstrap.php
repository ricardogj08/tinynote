<?php

require __DIR__ . '/vendor/autoload.php';

/*
 * Solución temporal si el servidor HTTP
 * no soporta reescritura de rutas.
 */
$_GET['_path_'] ??= ltrim($_SERVER['PATH_INFO'] ?? '', '/');

/*
 * Carga variables de entorno desde el archivo .env
 */
\App\Utils\Env::loadDotEnv();

/*
 * Carga opciones de configuración de la aplicación.
 */
foreach (\App\Utils\Config::getFromFilename('app') as $key => $value) {
    \App\Utils\Env::set('APP_' . strtoupper($key), $value);
}

/*
 * Carga opciones de configuración de la base de datos
 * para el ORM de la aplicación.
 */
foreach (\App\Utils\Config::getFromFilename('database') as $database => $options) {
    foreach ($options as $option => $value) {
        \App\Utils\Env::put(strtoupper($database . '_' . $option), $value);
    }
}

/*
 * Configura los logs de la aplicación.
 */
foreach (\App\Utils\Config::getFromFilename('logs') as $key => $value) {
    ini_set($key, $value);
}

/*
 * Configura e inicia una nueva sesión.
 */
session_start(['save_path' => __DIR__ . '/writable/sessions']);

/*
 * Crea una instancia de la aplicación.
 */
$app = new \Riverside\Express\Application();

/*
 * Configura la ruta de las vistas.
 */
$app->set('views', __DIR__ . '/app/Views');

/*
 * Establece parámetros personalizados
 * para utilizarlos en las rutas.
 */
$app->param('uuid', '[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}');
$app->param('wildcard', '.*');

/*
 * Carga los archivos de definición de rutas y middlewares.
 *
 * Se pasa la variable $app dentro de los archivos
 * para registrar rutas y middlewares.
 */
require __DIR__ . '/routes/api.php';
require __DIR__ . '/routes/web.php';

/*
 * Ejecuta la aplicación.
 */
$app->run();
