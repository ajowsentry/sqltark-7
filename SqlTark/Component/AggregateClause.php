<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class AggregateClause extends AbstractComponent
{
    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var Query|AbstractExpression
     */
    protected $column;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setType(string $value): void
    {
        $this->type = $value;
    }

    /**
     * @return Query|AbstractExpression
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param Query|AbstractExpression $value
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