<?php

declare(strict_types = 1);

namespace SqlTark\Component;

class LimitClause extends AbstractComponent
{
    /**
     * @var int $limit
     */
    protected $limit = 0;

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setLimit(int $value): void
    {
        $this->limit = $value < 0 ? 0 : $value;
    }

    /**
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit > 0;
    }
}