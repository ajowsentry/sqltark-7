<?php

declare(strict_types = 1);

namespace SqlTark\Component;

class OffsetClause extends AbstractComponent
{
    /**
     * @var int $offset
     */
    protected $offset = 0;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setOffset(int $value): void
    {
        $this->offset = $value < 0 ? 0 : $value;
    }

    /**
     * @return bool
     */
    public function hasOffset(): bool
    {
        return $this->offset > 0;
    }
}