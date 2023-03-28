<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query\Join;
use SqlTark\Utilities\Helper;

class JoinClause extends AbstractJoin
{
    /**
     * @var Join
     */
    protected $join;

    /**
     * @return Join
     */
    public function getJoin(): Join
    {
        return $this->join;
    }

    /**
     * @param Join $value
     * @return void
     */
    public function setJoin(Join $value): void
    {
        $this->join = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->join = Helper::clone($this->join);
    }
}
