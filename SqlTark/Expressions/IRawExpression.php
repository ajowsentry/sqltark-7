<?php
declare(strict_types=1);

namespace SqlTark\Expressions;

interface IRawExpression
{
    /**
     * @return string
     */
    public function getExpression(): string;

    /**
     * @param string $value
     * @return void
     */
    public function setExpression(string $value): void;

    /**
     * @return list<AbstractExpression>
     */
    public function getBindings(): iterable;

    /**
     * @param list<AbstractExpression> $value
     * @return void
     */
    public function setBindings(iterable $value): void;
}