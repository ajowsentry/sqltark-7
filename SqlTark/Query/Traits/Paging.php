<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\LimitClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\ComponentType;

trait Paging
{
    /**
     * @param int|float|string $limit
     * @return $this Self object
     */
    public function limit($limit)
    {
        $component = new LimitClause;
        $component->setLimit(intval($limit));

        return $this->addOrReplaceComponent(ComponentType::Limit, $component);
    }

    /**
     * @param int|float|string $offset
     * @return $this Self object
     */
    public function offset($offset)
    {
        $component = new OffsetClause;
        $component->setOffset(intval($offset));

        return $this->addOrReplaceComponent(ComponentType::Offset, $component);
    }

    /**
     * @param int|float|string $take
     * @return $this Self object
     */
    public function take($take)
    {
        return $this->limit($take);
    }

    /**
     * @param int|float|string $skip
     * @return $this Self object
     */
    public function skip($skip)
    {
        return $this->offset($skip);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return $this Self object
     */
    public function forPage(int $page, int $perPage = 20)
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }
}