<?php

declare(strict_types=1);

namespace SqlTark\Manager;

use SqlTark\Compiler\AbstractCompiler;
use SqlTark\Connection\AbstractConnection;
use SqlTark\Connection\Config as ConnectionConfig;

class Config extends ConnectionConfig
{
    /**
     * @var string $connection
     */
    private $connection;

    /**
     * @var string $compiler
     */
    private $compiler;

    /**
     * @var bool $wrapIdentifier
     */
    private $wrapIdentifier = true;

    /**
     * @var list<string> $failoverConnections
     */
    private $failoverConnections = [];

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        if(isset($config['driver'])) {
            $this->connection = $config['driver'];
            $this->compiler = $config['driver'];
        }

        if (isset($config['connection'])) {
            $this->connection = $config['connection'];
        }

        if (isset($config['compiler'])) {
            $this->compiler = $config['compiler'];
        }

        if (isset($config['wrap_identifier'])) {
            $this->wrapIdentifier = $config['wrap_identifier'];
        }

        if (isset($config['failover'])) {
            $this->failoverConnections = array_unique($config['failover']);
        }
    }

    /**
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getCompiler(): string
    {
        return $this->compiler;
    }

    /**
     * @return bool
     */
    public function isWrapIdentifier(): bool
    {
        return $this->wrapIdentifier;
    }

    /**
     * @return list<string>
     */
    public function getFailoverConnections(): array
    {
        return $this->failoverConnections;
    }

    /**
     * @template T of AbstractConnection
     * @param class-string<T> $value
     */
    public function setConnection(string $value): void
    {
        $this->connection = $value;
    }

    /**
     * @template T of AbstractCompiler
     * @param class-string<T> $value
     */
    public function setCompiler(string $value): void
    {
        $this->compiler = $value;
    }

    /**
     * @param list<string> $value
     */
    public function setFailoverConnections(array $value): void
    {
        $this->failoverConnections = $value;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setWrapIdentifier($value): void
    {
        $this->wrapIdentifier = $value;
    }
}