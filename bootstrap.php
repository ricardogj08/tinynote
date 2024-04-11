<?php

require __DIR__ . '/vendor/autoload.php';

/*
 * Solución temporal si el servidor HTTP
 * no soporta reescritura de rutas.
 */
$_GET['_path_'] = preg_replace('/^(\/?index\.php)?\/?/', '', $_SERVER['REQUEST_URI'] ?? '/');

/*
 * Carga variables de entorno desde el archivo .env
 */
\App\Utils\Env::loadDotEnv();

/*
 * Carga opciones de configuración de la aplicación.
 */
foreach (\App\Utils\Config::getFromFile('app') as $key => $value) {
    \App\Utils\Env::set('APP_' . strtoupper($key), $value);
}

/*
 * Establece la configuración de la base de datos
 * para el ORM de la aplicación.
 */
\PhpOrm\DB::config(__DIR__ . '/config/database.php');

/*
 * Configura e inicia una nueva sesión.
 */
session_save_path(__DIR__ . '/writable/sessions');
session_start();

/*
 * Crea una instancia de la aplicación.
 */
$app = new \PhpExpress\Application();

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
require_once __DIR__ . '/routes/api.php';
require_once __DIR__ . '/routes/web.php';

/*
 * Ejecuta la aplicación.
 */
$app->run();
