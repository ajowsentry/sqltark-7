<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class BetweenCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected $column;

    /**
     * @var AbstractExpression|Query $lower
     */
    protected $lower;

    /**
     * @var AbstractExpression|Query $higher
     */
    protected $higher;

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
     * @return AbstractExpression|Query
     */
    public function getLower()
    {
        return $this->lower;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setLower($value): void
    {
        $this->lower = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getHigher()
    {
        return $this->higher;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setHigher($value): void
    {
        $this->higher = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->lower = Helper::clone($this->lower);
        $this->column = Helper::clone($this->column);
        $this->higher = Helper::clone($this->higher);
    }
}
