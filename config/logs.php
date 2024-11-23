<?php

/*
 * Opciones de configuración de los logs.
 */

return (static function () {
    $options = [
        'error_reporting' => (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED),
        'display_errors' => false,
        'display_startup_errors' => false,
        'log_errors' => true,
        'error_log' => '../writable/errors.log',
        'ignore_repeated_errors' => 1,
        'ignore_repeated_source' => 1
    ];

    if (\App\Utils\Env::get('APP_ENVIRONMENT') === 'development') {
        $options = array_merge($options, [
            'error_reporting' => E_ALL,
            'display_errors' => true,
            'display_startup_errors' => true
        ]);
    }

    return $options;
})();
