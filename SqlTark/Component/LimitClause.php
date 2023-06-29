<?php

declare(strict_types = 1);

namespace SqlTark\Component;

use SqlTark\Expressions\Variable;

class LimitClause extends AbstractComponent
{
    /**
     * @var int|Variable $limit
     */
    protected $limit = 0;

    /**
     * @return int|Variable
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int|Variable $value
     * @return void
     */
    public function setLimit($value): void
    {
        $this->limit = $value instanceof Variable ? $value : (
            $value < 0 ? 0 : $value
        );
    }

    /**
     * @return bool
     */
    public function hasLimit(): bool
    {
        return $this->limit instanceof Variable || $this->limit > 0;
    }
}