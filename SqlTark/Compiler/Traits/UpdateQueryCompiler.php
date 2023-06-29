<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\LimitClause;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\UpdateClause;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractCondition;
use SqlTark\Expressions\AbstractExpression;

trait UpdateQueryCompiler
{
    use ExpressionCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileUpdateQuery(Query $query): string
    {
        /** @var ?AbstractFrom $from */
        $from = $query->getOneComponent(ComponentType::From);

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

        /** @var ?UpdateClause $update */
        $update = $query->getOneComponent(ComponentType::Update);


        if(empty($from)) {
            throw new InvalidArgumentException("Table not specified!");
        }

        if(empty($update)) {
            throw new InvalidArgumentException("Update value not specified!");
        }

        $result = 'UPDATE ' . $this->compileFrom($from);

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
        }

        $expressionResolver = function ($expression) {

            if ($expression instanceof AbstractExpression) {
                return $this->compileExpression($expression, false);
            } elseif ($expression instanceof Query) {
                $resolvedValue = $this->compileQuery($expression);
                $resolvedValue = "($resolvedValue)";
                return $resolvedValue;
            }
        };

        if($update instanceof UpdateClause) {
            $result .= ' SET ';
            $first = true;
            foreach($update->getValues() as $column => $value) {
                if(!$first) $result .= ', ';
                $result .= $this->wrapIdentifier($column);
                $result .= ' = ';
                $result .= $expressionResolver($value);
                $first = false;
            }
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