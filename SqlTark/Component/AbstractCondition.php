<?php

declare(strict_types=1);

namespace SqlTark\Component;

abstract class AbstractCondition extends AbstractComponent
{
    /**
     * @var bool $isOr
     */
    protected $isOr = false;

    /**
     * @var bool $isNot
     */
    protected $isNot = false;

    /**
     * @return bool
     */
    public function getOr(): bool
    {
        return $this->isOr;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setOr(bool $value): void
    {
        $this->isOr = $value;
    }

    /**
     * @return bool
     */
    public function getNot(): bool
    {
        return $this->isNot;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setNot(bool $value): void
    {
        $this->isNot = $value;
    }
}
