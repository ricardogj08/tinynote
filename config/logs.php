<?php

use App\Utils\Env;

/*
 * Opciones de configuración de los logs.
 */

return (static function () {
    $environment = Env::get('APP_ENVIRONMENT');

    $is_dev = $environment === 'development';
    $is_prod = $environment === 'production';

    return [
        'error_reporting' => ($is_dev ? E_ALL : (E_ALL & ~E_DEPRECATED & ~E_STRICT)),
        'display_errors' => $is_dev,
        'display_startup_errors' => $is_dev,
        'log_errors' => $is_prod,
        'error_log' => '../writable/errors.log',
        'ignore_repeated_errors' => 1,
        'ignore_repeated_source' => 1
    ];
})();
