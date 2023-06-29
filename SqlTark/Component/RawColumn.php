<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Utilities\Helper;
use SqlTark\Expressions\IRawExpression;
use SqlTark\Expressions\AbstractExpression;

class RawColumn extends AbstractColumn implements IRawExpression
{
    /**
     * @var string $expression
     */
    protected $expression;

    /**
     * @var list<AbstractExpression> $bindings
     */
    protected $bindings;

    /** {@inheritDoc} */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /** {@inheritDoc} */
    public function setExpression(string $value): void
    {
        $this->expression = $value;
    }

    /** {@inheritDoc} */
    public function getBindings(): iterable
    {
        return $this->bindings;
    }

    /** {@inheritDoc} */
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
