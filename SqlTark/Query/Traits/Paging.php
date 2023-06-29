<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\Variable;

trait Paging
{
    /**
     * @param int|float|string|Variable $limit
     * @return static Self object
     */
    public function limit($limit)
    {
        if(!($limit instanceof Variable))
            $limit = intval($limit);

        $component = new LimitClause;
        $component->setLimit($limit);

        return $this->addOrReplaceComponent(ComponentType::Limit, $component);
    }

    /**
     * @param int|float|string|Variable $offset
     * @return static Self object
     */
    public function offset($offset)
    {
        if(!($offset instanceof Variable))
            $offset = intval($offset);

        $component = new OffsetClause;
        $component->setOffset($offset);

        return $this->addOrReplaceComponent(ComponentType::Offset, $component);
    }

    /**
     * @param int|float|string|Variable $take
     * @return static Self object
     */
    public function take($take)
    {
        return $this->limit($take);
    }

    /**
     * @param int|float|string|Variable $skip
     * @return static Self object
     */
    public function skip($skip)
    {
        return $this->offset($skip);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return static Self object
     */
    public function forPage(int $page, int $perPage = 20)
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }
}