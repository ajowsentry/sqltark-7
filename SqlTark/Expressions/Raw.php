<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

final class Raw extends AbstractExpression
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
     * @param string $expression
     * @param list<AbstractExpression> $bindings
     */
    public function __construct(string $expression, iterable $bindings = [])
    {
        $this->expression = $expression;
        $this->bindings = $bindings;
    }
}
