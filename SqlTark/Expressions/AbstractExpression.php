<?php

declare(strict_types=1);

namespace SqlTark\Expressions;

abstract class AbstractExpression
{
    /**
     * @var ?string $wrap
     */
    protected $wrap = null;

    /**
     * @return ?string
     */
    public function getWrap(): ?string
    {
        return $this->wrap;
    }

    /**
     * @param ?string $value
     * @return $this Self object
     */
    public function wrap(?string $value)
    {
        $this->wrap = $value;
        return $this;
    }
}