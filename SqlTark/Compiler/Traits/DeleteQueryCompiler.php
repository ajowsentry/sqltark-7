<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\LimitClause;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractCondition;

trait DeleteQueryCompiler
{
    use ExpressionCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileDeleteQuery(Query $query): string
    {
        /** @var list<AbstractFrom> $tables */
        $tables = $query->getComponents(ComponentType::From);

        /** @var list<AbstractJoin> $joins */
        $joins = $query->getComponents(ComponentType::Join);

        /** @var list<AbstractCondition> $where */
        $where = $query->getComponents(ComponentType::Where);

        /** @var list<AbstractOrder> $orderBy */
        $orderBy = $query->getComponents(ComponentType::OrderBy);

        /** @var ?LimitClause $limit */
        $limit = $query->getOneComponent(ComponentType::Limit);

        /** @var ?OffsetClause $offset */
        $offset = $query->getOneComponent(ComponentType::Offset);

        if(empty($tables)) {
            throw new InvalidArgumentException("Table not specified!");
        }

        $result = 'DELETE ';
        if(count($tables) > 1) {
            $result .= $this->compileTables($tables);
        }

        $result .= ' FROM ' . $this->compileFrom($tables[0]);

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
        }

        $resolvedWhere = $this->compileWhere($where);
        if($resolvedWhere) {
            $result .= ' ' . $resolvedWhere;
        }

        $resolvedOrderBy = $this->compileOrderBy($orderBy);
        if($resolvedOrderBy) {
            $result .= ' ' . $resolvedOrderBy;
        }

        $resolvedPaging = $this->compilePaging($limit, $offset);
        if($resolvedPaging) {
            $result .= ' ' . $resolvedPaging;
        }

        return $result;
    }
}