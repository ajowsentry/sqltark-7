<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;

class CombineClause extends AbstractComponent
{
    /**
     * @var Query $query
     */
    protected $query;

    /**
     * @var int $operation From CombineType enum
     */
    protected $operation;

    /**
     * @var bool $all
     */
    protected $all = false;

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param Query $value
     * @return void
     */
    public function setQuery(Query $value): void
    {
        $this->query = $value;
    }

    /**
     * @return int CombineType enum
     */
    public function getOperation(): int
    {
        return $this->operation;
    }

    /**
     * @param int $value CombineType enum
     * @return void
     */
    public function setOperation(int $value): void
    {
        $this->operation = $value;
    }

    /**
     * @return bool
     */
    public function isAll(): bool
    {
        return $this->all;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setAll(bool $value): void
    {
        $this->all = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->query = Helper::clone($this->query);
    }
}
