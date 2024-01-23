<?php

namespace App\Utils;

use Exception;

class DB
{
    private $database = 'default';
    private $config;
    private $connection;

    public function __construct()
    {
        $this->mount();
    }

    private function mount()
    {
        $options = require __DIR__ . '/../../config/database.php';

        if (!isset($options[$this->database])) {
            throw new Exception('Database connection not found.');
        }

        $opts = $options[$this->database];

        foreach (['username', 'password', 'database', 'host', 'port', 'driver', 'charset', 'collation'] as $key) {
            if (!array_key_exists($key, $opts)) {
                throw new Exception("The '{$key}' index was not found in database config.");
            }
        }

        $this->config = new \PhpOrm\Configuration(
            $opts['username'],
            $opts['password'],
            $opts['database'],
            $opts['host'],
            $opts['port'],
            $opts['driver'],
            $opts['charset'],
            $opts['collation']
        );

        $this->connection = new \PhpOrm\Connection($this->config);
    }

    /*
     * Obtiene opciones de configuración
     * de la base de datos.
     */
    public function getConfig()
    {
        return $this->config;
    }

    /*
     * Obtiene opciones de configuración
     * de conexión de la base de datos.
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
