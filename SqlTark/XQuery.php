<?php

declare(strict_types=1);

namespace SqlTark;

use PDO;
use PDOException;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\XPDOStatement;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Connection\AbstractConnection;
use SqlTark\Expressions\AbstractExpression;

class XQuery extends Query
{
    /**
     * @var ?AbstractConnection $connection
     */
    private $connection = null;

    /**
     * @var bool $resetOnExecute
     */
    private $resetOnExecute = true;

    /**
     * @var null|(callable(string,?array<mixed>,?XPDOStatement):void) $onExecuteCallback
     */
    private $onExecuteCallback = null;

    /**
     * @return AbstractConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param AbstractConnection $connection
     * @return static
     */
    public function setConnection(AbstractConnection $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return bool
     */
    public function isResetOnExecute(): bool
    {
        return $this->resetOnExecute;
    }

    /**
     * @param (callable(string,?array<mixed>,?XPDOStatement):void) $onExecuteCallback
     * @return $this Self object
     */
    public function onExecute(callable $onExecuteCallback)
    {
        $this->onExecuteCallback = $onExecuteCallback;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this Self object
     */
    public function resetOnExecute(bool $value = true)
    {
        $this->resetOnExecute = $value;
        return $this;
    }

    /**
     * @return $this Self object
     */
    public function reset()
    {
        if(!is_null($this->components)) {
            $this->clearComponents();
        }

        $this->method = MethodType::Select;

        return $this;
    }

    /**
     * @param int $index
     * @param mixed $value
     * @param list<mixed> $types
     * @return int
     */
    private function determineType(int $index, $value, array $types): int
    {
        if(array_key_exists($index, $types)) {
            return $types[$index];
        }

        switch (Helper::getType($value)) {
            case 'bool'    : return PDO::PARAM_BOOL;
            case 'int'     : return PDO::PARAM_INT;
            case 'integer' : return PDO::PARAM_INT;
            case 'null'    : return PDO::PARAM_NULL;
        };

        return PDO::PARAM_STR;
    }

    /**
     * @param null|Query|string $query
     * @param list<mixed> $params
     * @param list<mixed> $types
     * @return XPDOStatement Statement
     */
    public function prepare($query = null, array $params = [], array $types = []): XPDOStatement
    {
        if(func_num_args() === 0) {
            $query = $this->compiler->compileQuery($this);
        }

        if ($query instanceof Query) {
            $sql = $this->compiler->compileQuery($query);
        }
        elseif (is_string($query)) {
            $sql = $query;
        }

        if (empty($sql)) {
            Helper::throwInvalidArgumentException("Could not resolve '%s'", $query);
        }

        try {
            $pdo = $this->connection->getPDO();

            $statement = $pdo->prepare($sql);
            if(false === $statement) {
                [$sqlState, $errorCode, $errorMessage] = $pdo->errorInfo();
                throw new PDOException("SQLSTATE[{$sqlState}]: (Code {$errorCode}) {$errorMessage}");
            }

            foreach($params as $index => $value) {
                $type = $this->determineType($index, $value, $types);
                $statement->bindValue(1 + $index, $value, $type);
            }

            /** @var XPDOStatement $statement */
            return $statement;
        }
        finally {
            if($this->resetOnExecute) {
                $this->reset();
            }
        }
    }

    /**
     * @param null|Query|string $query
     * @param list<mixed> $params
     * @param list<mixed> $types
     * @return XPDOStatement Statement
     */
    public function execute($query = null, array $params = [], array $types = []): XPDOStatement
    {
        if ($query instanceof Query) {
            $sql = $this->compiler->compileQuery($query);
        }
        elseif (is_string($query)) {
            $sql = $query;
        }
        else {
            $sql = $this->compiler->compileQuery($this);
        }

        if (empty($sql)) {
            Helper::throwInvalidArgumentException("Could not resolve '%s'", $query);
        }

        try {
            $statement = $this->prepare($sql, $params, $types);
            if(false === $statement->execute()) {
                [$sqlState, $errorCode, $errorMessage] = $this->getConnection()->getPDO()->errorInfo();
                throw new PDOException("SQLSTATE[{$sqlState}]: (Code {$errorCode}) {$errorMessage}");
            }

            return $statement;
        }
        finally {
            if(isset($statement)) {
                $this->triggerOnExecute($sql, $statement->errorInfo(), $statement);
            }
            else {
                $this->triggerOnExecute($sql);
            }
        }
    }

    /**
     * @param string $name
     * [optional] Name of the sequence object from which the ID should be returned.
     * 
     * @return string|false
     * If a sequence name was not specified for the name parameter, PDO::lastInsertId
     * returns a string representing the row ID of the last row that was inserted
     * into the database.
     */
    public function lastInsertId(?string $name = null)
    {
        return $this->connection->getPDO()->lastInsertId($name);
    }

    /**
     * @return bool
     */
    public function transaction(): bool
    {
        return $this->connection->transaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->connection->rollback();
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return ?T
     */
    public function getOne(?string $class = null)
    {
        $this->method = MethodType::Select;

        $statement = $this->limit(1)->execute($this);

        try {
            $result = !is_null($class)
                ? $statement->fetch(PDO::FETCH_ASSOC)
                : $statement->fetch();

            if(false === $result)
                return null;

            return !is_null($class)
                ? new $class($result)
                : $result;
        }
        finally {
            $statement->closeCursor();
        }
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return list<T>
     */
    public function getAll(?string $class = null): array
    {
        return iterator_to_array($this->getIterate($class));
    }

    /**
     * @template T
     * @param ?class-string<T> $class
     * @return iterable<T>
     */
    public function getIterate(?string $class = null): iterable
    {
        $this->method = MethodType::Select;
        $statement = $this->execute($this);

        try {
            if(!is_null($class)) {
                while(false !== ($row = $statement->fetch(PDO::FETCH_ASSOC))) {
                    yield new $class($row);
                }
            }
            else {
                while(false !== ($row = $statement->fetch())) {
                    yield $row;
                }
            }
        }
        finally {
            $statement->closeCursor();
        }
    }

    /**
     * @param int $columnIndex
     * @return mixed
     */
    public function getScalar(int $columnIndex = 0)
    {
        $this->method = MethodType::Select;

        $statement = $this->execute($this);
        $result = $statement->fetchColumn($columnIndex);
        $statement->closeCursor();

        return $result;
    }

    /**
     * @return XPDOStatement
     */
    public function delete(string ...$tables): XPDOStatement
    {
        return $this->asDelete(...$tables)->execute();
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return XPDOStatement
     */
    public function insert(array $keyValues): XPDOStatement
    {
        return $this->asInsert($keyValues)->execute();
    }

    /**
     * @param list<string> $columns
     * @param list<list<AbstractExpression|Query>> $values
     * @return XPDOStatement
     */
    public function bulkInsert(iterable $columns, iterable $values): XPDOStatement
    {
        return $this->asBulkInsert($columns, $values)->execute();
    }

    /**
     * @param (\Closure(Query):void)|Query $query
     * @param ?list<string> $columns
     * @return XPDOStatement
     */
    public function insertWith($query, ?iterable $columns = null): XPDOStatement
    {
        return $this->asInsertWith($query, $columns)->execute();
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return XPDOStatement
     */
    public function update(array $keyValues): XPDOStatement
    {
        return $this->asUpdate($keyValues)->execute();
    }

    /**
     * @param string $sql
     * @param ?list<mixed> $errorInfo
     * @param ?XPDOStatement $statement
     * @return void
     */
    private function triggerOnExecute(string $sql, ?array $errorInfo = null, ?XPDOStatement $statement = null): void
    {
        if(is_callable($this->onExecuteCallback)) {
            call_user_func_array($this->onExecuteCallback, [$sql, $errorInfo, $statement]);
        }
    }
}