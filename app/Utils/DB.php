<?php

namespace App\Utils;

use AbmmHasan\UUID\GenerateUuid;
use App\Utils\Config;
use Riverside\Orm\Configuration;
use Riverside\Orm\Connection;

class DB
{
    private const configFilename = 'database';
    private const database = 'default';

    private $configuration;
    private $connection;

    public function __construct()
    {
        $this->mount();
    }

    private function mount()
    {
        $config = Config::getFromFilename(self::configFilename);

        $options = $config[self::database];

        $this->configuration = new Configuration(
            $options['username'],
            $options['password'],
            $options['database'],
            $options['host'],
            $options['port'],
            $options['driver'],
            $options['charset'],
            $options['collation']
        );

        $this->connection = new Connection($this->configuration);
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

    /*
     * Obtiene el datetime actual.
     */
    public static function datetime()
    {
        return date('Y-m-d H:i:s');
    }

    /*
     * Genera un UUID v4 aleatorio.
     */
    public static function generateUuid()
    {
        return GenerateUuid::v4();
    }
}
