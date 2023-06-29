<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

use SqlTark\Utilities\Helper;

final class Raw extends AbstractExpression implements IRawExpression
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
        return array_map(function($item) {
            return Helper::resolveExpression($item);
        }, $this->bindings);
    }

    /** {@inheritDoc} */
    public function setBindings(iterable $value): void
    {
        $this->bindings = $value;
    }

    /**
     * @param string $expression
     * @param list<AbstractExpression> $bindings
     */
    public function __construct(string $expression, iterable $bindings = [])
    {
        $this->expression = $expression;
        $this->bindings = $bindings;
    }
}
