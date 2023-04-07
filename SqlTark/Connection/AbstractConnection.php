<?php

declare(strict_types=1);

namespace SqlTark\Connection;

use InvalidArgumentException;
use PDO;
use PDOException;

abstract class AbstractConnection
{
    /**
     * @var ?PDO $pdo
     */
    protected $pdo = null;

    /**
     * @var string $driver
     */
    protected $driver = '';

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var int $transactionCount
     */
    protected $transactionCount = 0;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        return $this->pdo ?? $this->connect();
    }

    /**
     * @param Config|array<string,mixed> $config
     */
    public function __construct($config = [])
    {
        $this->config = $config instanceof Config ? $config : new Config($config);
    }

    /**
     * @return string
     */
    abstract protected function createDSN(): string;

    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * @return false|PDO
     */
    public function connect(bool $throwOnError = true)
    {
        if ($throwOnError && !in_array($this->driver, PDO::getAvailableDrivers(), true)) {
            throw new InvalidArgumentException(
                "PDO driver '{$this->driver}' not available"
            );
        }

        try {
            $this->pdo = new PDO(
                $this->createDSN(),
                $this->config->getUsername(),
                $this->config->getPassword(),
                $this->config->getAttributes()
            );

            $this->onConnected();
            return $this->pdo;
        }
        catch(PDOException $ex) {
            if ($throwOnError)
                throw $ex;

            return false;
        }
    }

    /**
     * @return bool
     */
    public function transaction(): bool
    {
        return $this->getPDO()->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getPDO()->commit();
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->getPDO()->rollBack();
    }

    /**
     * @return void
     */
    public function resetTransactionState(): void
    {
        $this->transactionCount = 0;
    }

    /**
     * @return void
     */
    protected function onConnected(): void { }
}