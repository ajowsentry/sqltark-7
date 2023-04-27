<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use InvalidArgumentException;
use SqlTark\Component\FromClause;
use SqlTark\Component\ComponentType;

trait Cte
{
    /**
     * @param (\Closure(Query):void)|Query $query
     * @param ?string $alias
     * @return $this Self object
     */
    public function with($query, ?string $alias = null)
    {
        $query = Helper::resolveQuery($query, $this);

        $alias = $alias ?? $query->getAlias();
        if(empty($alias)) {
            throw new InvalidArgumentException(
                "No alias found for CTE query"
            );
        }

        $component = new FromClause;
        $component->setTable($query);
        $component->setAlias($alias);

        return $this->addComponent(ComponentType::CTE, $component);
    }
}