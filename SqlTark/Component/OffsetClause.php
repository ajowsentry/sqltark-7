<?php

declare(strict_types = 1);

namespace SqlTark\Component;

use SqlTark\Expressions\Variable;

class OffsetClause extends AbstractComponent
{
    /**
     * @var int|Variable $offset
     */
    protected $offset = 0;

    /**
     * @return int|Variable
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int|Variable $value
     * @return void
     */
    public function setOffset($value): void
    {
        $this->offset = $value instanceof Variable ? $value : (
            $value < 0 ? 0 : $value
        );
    }

    /**
     * @return bool
     */
    public function hasOffset(): bool
    {
        return $this->offset instanceof Variable || $this->offset > 0;
    }
}