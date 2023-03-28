<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractFrom extends AbstractComponent
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
}
