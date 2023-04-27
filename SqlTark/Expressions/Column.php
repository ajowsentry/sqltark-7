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
     * @var ?string $alias
     */
    private $alias = null;

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
     * @return ?string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param ?string $value
     * @return void
     */
    public function setAlias(?string $value): void
    {
        $this->alias = $value;
    }

    /**
     * @param string $value
     * @return $this Self object
     */
    public function as(string $value)
    {
        $this->alias = $value;
        return $this;
    }

    /**
     * @param string $name
     */
    public function __construct(string $name, ?string $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias;
    }
}
