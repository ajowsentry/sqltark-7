<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class InCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected $column;

    /**
     * @var list<AbstractExpression>|Query $values
     */
    protected $values;

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
     * @return list<AbstractExpression>|Query
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param list<AbstractExpression>|Query $value
     * @return void
     */
    public function setValues($value): void
    {
        $this->values = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->column = Helper::clone($this->column);
        $this->values = Helper::clone($this->values);
    }
}
