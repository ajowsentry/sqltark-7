<?php

declare(strict_types=1);

namespace SqlTark\Compiler\SqlServer;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\LimitClause;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractCondition;
use SqlTark\Compiler\Traits\ExpressionCompiler;

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

        /** @var ?LimitClause $limit */
        $limit = $query->getOneComponent(ComponentType::Limit);

        if(empty($tables)) {
            throw new InvalidArgumentException("Table not specified!");
        }

        $result = 'DELETE ';

        if(!is_null($limit)) {
            $result .= 'TOP (' . $limit->getLimit() . ') ';
        }
        
        $result .= 'FROM ' . $this->compileFrom($tables[0]);

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
        }

        $resolvedWhere = $this->compileWhere($where);
        if($resolvedWhere) {
            $result .= ' ' . $resolvedWhere;
        }

        return $result;
    }
}