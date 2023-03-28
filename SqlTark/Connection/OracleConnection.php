<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use SqlTark\Connection\AbstractConnection;

class OracleConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected $driver = 'oci';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dbname = $database;
        if(!empty($host) || !empty($port)) {
            $host = $host ?: 'localhost';
            if(!empty($port))
                $host .= ':' . $port;
            $dbname = "//{$host}/{$dbname}";
        }

        $dsn = "oci:dbname={$dbname}";
        $charset = $this->config->getExtras('charset');
        if(!is_null($charset)) {
            $dsn .= ';charset=' . $charset;
        }

        return $dsn;
    }
}