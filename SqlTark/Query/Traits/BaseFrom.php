<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;

trait BaseFrom
{
    /**
     * @var ?string $alias
     */
    protected $alias = null;

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
     * @param ?string $alias
     * @return static Self object
     */
    public function alias(?string $alias)
    {
        $this->setAlias($alias);
        return $this;
    }

    /**
     * @param string $table
     * @param ?string $alias
     * @return static Self object
     */
    public function from(string $table, ?string $alias = null)
    {
        $component = new FromClause;
        $component->setTable($table);
        $component->setAlias($alias);

        return $this->addOrReplaceComponent(ComponentType::From, $component);
    }
}