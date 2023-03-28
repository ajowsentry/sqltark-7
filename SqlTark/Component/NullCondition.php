<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class NullCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected $column;

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
     * @return void
     */
    public function __clone()
    {
        $this->column = Helper::clone($this->column);
    }
}
