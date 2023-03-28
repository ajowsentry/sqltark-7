<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

final class Column extends AbstractExpression
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setName(string $value): void
    {
        $this->name = $value;
    }

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
