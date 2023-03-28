<?php

declare(strict_types=1);

namespace SqlTark\Connection;

class MySqlConnection extends AbstractConnection
{
    /**
     * {@inheritdoc}
     */
    protected $driver = 'mysql';

    /**
     * {@inheritdoc}
     */
    protected function createDSN(): string
    {
        $host = $this->config->getHost();
        $port = $this->config->getPort();
        $database = $this->config->getDatabase();
        $charset = $this->config->getExtras('charset', 'utf8');

        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";
        if(!is_null($port)) {
            $dsn .= ";port={$port}";
        }

        return $dsn;
    }

    /**
     * {@inheritdoc}
     */
    protected function onConnected(): void
    {
        $charset = $this->config->getExtras('charset', 'utf8');
        $collation = $this->config->getExtras('collation', 'utf8_unicode_ci');

        $this->pdo->exec("SET NAMES '{$charset}' COLLATE '{$collation}'");
    }

    /**
     * {@inheritdoc}
     */
    public function transaction(): bool
    {
        if($this->transactionCount++ === 0) {
            return $this->getPDO()->beginTransaction();
        }

        $this->getPDO()->exec("SAVEPOINT __trx_{$this->transactionCount}__");
        return $this->transactionCount >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        if(--$this->transactionCount === 0) {
            return $this->getPDO()->commit();
        }

        return $this->transactionCount >= 0;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback(): bool
    {
        if($this->transactionCount > 1) {
            $this->getPDO()->exec("ROLLBACK TO __trx_{$this->transactionCount}__");
            $this->transactionCount--;
            return true;
        }

        $this->transactionCount--;
        return $this->getPDO()->rollBack();
    }
}