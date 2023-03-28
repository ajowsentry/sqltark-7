<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class RawFrom extends AbstractFrom
{
    /**
     * @var string $expression
     */
    protected $expression;

    /**
     * @var list<AbstractExpression> $bindings
     */
    protected $bindings;

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setExpression(string $value): void
    {
        $this->expression = $value;
    }

    /**
     * @return list<AbstractExpression>
     */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /**
     * @param list<AbstractExpression> $value
     * @return void
     */
    public function setBindings(iterable $value): void
    {
        $this->bindings = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->bindings = Helper::clone($this->bindings);
    }
}
