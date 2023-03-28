<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;

class ExistsCondition extends AbstractCondition
{
    /**
     * @var Query $query
     */
    protected $query;

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param Query $value
     * @return void
     */
    public function setQuery(Query $value): void
    {
        $this->query = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->query = Helper::clone($this->query);
    }
}
