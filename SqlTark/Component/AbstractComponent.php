<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractComponent
{
    /**
     * @var int $componentType ComponentType enum
     */
    protected $componentType;

    /**
     * @return int ComponentType enum
     */
    public function getComponentType(): int
    {
        return $this->componentType;
    }

    /**
     * @param int $componentType ComponentType enum
     * @return void
     */
    public function setComponentType(int $componentType): void
    {
        $this->componentType = $componentType;
    }

    public final function __construct() { }
}