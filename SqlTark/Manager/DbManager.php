<?php

declare(strict_types=1);

namespace SqlTark\Manager;

use SqlTark\XQuery;
use RuntimeException;
use SqlTark\Compiler\MySqlCompiler;
use SqlTark\Compiler\AbstractCompiler;
use SqlTark\Compiler\PostgresCompiler;
use SqlTark\Compiler\SqlServerCompiler;
use SqlTark\Compiler\SqlServerLegacyCompiler;
use SqlTark\Connection\MySqlConnection;
use SqlTark\Connection\AbstractConnection;
use SqlTark\Connection\DbLibConnection;
use SqlTark\Connection\OracleConnection;
use SqlTark\Connection\PostgresConnection;
use SqlTark\Connection\SqlServerConnection;

class DbManager
{
    /**
     * @var array<string,Config> $configurations
     */
    private $configurations = [];

    /**
     * @var array<string,XQuery> $connections
     */
    private $connections = [];

    /**
     * @var string $defaultConnection
     */
    private $defaultConnection = 'main';

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config = [])
    {
        if(!empty($config['default'])) {
            $this->defaultConnection = $config['default'];
        }
        
        if(!empty($config['connections'])) {
            foreach($config['connections'] as $key => $value) {
                $this->configurations[$key] = new Config($value);
            }
        }
    }

    /**
     * @param string $connectionName
     * @param bool $failsafe
     * @return XQuery
     */
    public function connection(?string $connectionName = null, bool $failsafe = true): XQuery
    {
        $connectionName = $connectionName ?? $this->defaultConnection;

        if(isset($this->connections[$connectionName])) {
            return $this->connections[$connectionName];
        }

        if(!isset($this->configurations[$connectionName])) {
            throw new RuntimeException("Invalid database configuration. Config '{$connectionName}' not found.");
        }

        $config = $this->configurations[$connectionName];
        $connection = $this->resolveConnection($config);

        if($failsafe && false === $connection->connect(false)) {
            foreach($config->getFailoverConnections() as $failoverName) {
                $xq = $this->connection($failoverName, false);
                if(false !== $xq->getConnection()->connect(false)) {
                    return $xq;
                }
            }
        }

        $compiler = $this->resolveCompiler($config);

        $xquery = new XQuery;
        $xquery->setCompiler($compiler);
        $xquery->setConnection($connection);

        return $this->connections[$connectionName] = $xquery;
    }

    /**
     * @param Config $config
     * @return AbstractConnection
     */
    private function resolveConnection(Config $config): AbstractConnection
    {
        $driver = $config->getConnection();
        switch($driver) {
            case 'mysql':
            case MySqlConnection::class:
            return new MySqlConnection($config);

            case 'sqlsrv':
            case SqlServerConnection::class:
            return new SqlServerConnection($config);

            case 'dblib':
            case DbLibConnection::class:
            return new DbLibConnection($config);

            case 'oci':
            case OracleConnection::class:
            return new OracleConnection($config);

            case 'pgsql':
            case PostgresConnection::class:
            return new PostgresConnection($config);
        }

        if(class_exists($driver) && is_subclass_of($driver, AbstractConnection::class)) {
            return new $driver($config);
        }

        throw new RuntimeException("Could not resolve connection for '{$driver}' driver.");
    }

    /**
     * @param Config $config
     * @return AbstractCompiler
     */
    private function resolveCompiler(Config $config): AbstractCompiler
    {
        $driver = $config->getCompiler();
        switch($driver) {
            case 'mysql':
            $driver = MySqlCompiler::class;
            break;

            case 'sqlsrv':
            case 'dblib':
            $driver = SqlServerCompiler::class;
            break;

            case 'sqlsrv_legacy':
            $driver = SqlServerLegacyCompiler::class;
            break;

            case 'pgsql':
            $driver = PostgresCompiler::class;
            break;
        }

        if(class_exists($driver) && is_subclass_of($driver, AbstractCompiler::class)) {
            /** @var AbstractCompiler */
            $compiler = new $driver;
            $compiler->isWrapIdentifier = $config->isWrapIdentifier();
            return $compiler;
        }

        throw new RuntimeException("Could not resolve compiler for '{$driver}' driver.");
    }
}