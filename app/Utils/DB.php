<?php

namespace App\Utils;

use Exception;

class DB
{
    private $configFile = 'database';
    private $database = 'default';
    private $configuration;
    private $connection;

    public function __construct()
    {
        $this->mount();
    }

    private function mount()
    {
        $config = Config::getFromFile(self::$configFile);

        $options = $config[$this->database];

        $this->configuration = new \PhpOrm\Configuration(
            $options['username'],
            $options['password'],
            $options['database'],
            $options['host'],
            $options['port'],
            $options['driver'],
            $options['charset'],
            $options['collation']
        );

        $this->connection = new \PhpOrm\Connection($this->configuration);
    }

    /*
     * Obtiene opciones de configuración
     * de la base de datos.
     */
    public function getConfig()
    {
        return $this->configuration;
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
