<?php
declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class OrderClause extends AbstractOrder
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected $column;

    /**
     * @var bool
     */
    protected $ascending = true;

    /**
     * @return AbstractExpression|Query
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setColumn($value): void
    {
        $this->column = $value;
    }

    /**
     * @return bool
     */
    public function isAscending(): bool
    {
        return $this->ascending;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setAscending(bool $value): void
    {
        $this->ascending = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->column = Helper::clone($this->column);
    }
}