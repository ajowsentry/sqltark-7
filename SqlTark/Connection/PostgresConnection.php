<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use SqlTark\Connection\AbstractConnection;

class PostgresConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected $driver = 'pgsql';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();

        $dsn = "pgsql:host={$host}";
        if(!is_null($port)) {
            $dsn .= ";port={$port}";
        }

        $dsn .= ";dbname={$database};user=" . $this->config->getUsername() . ";password=" . $this->config->getPassword();

        if(!is_null($sslmode = $this->config->getExtras('sslmode'))) {
            $dsn .= ";sslmode={$sslmode}";
        }

        return $dsn;
    }
}