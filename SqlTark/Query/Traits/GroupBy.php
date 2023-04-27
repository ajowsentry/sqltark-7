<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawColumn;
use SqlTark\Component\ColumnClause;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions\AbstractExpression;

trait GroupBy
{
    /**
     * @param null|scalar|DateTimeInterface|Query ...$columns
     * @return $this Self object
     */
    public function groupBy(...$columns)
    {
        foreach ($columns as $column) {
            $column = Helper::resolveExpression($column, true);

            $component = new ColumnClause;
            $component->setColumn($column);

            $this->addComponent(ComponentType::GroupBy, $component);
        }

        return $this;
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression ...$bindings
     * @return $this Self object
     */
    public function groupByRaw(string $expression, ...$bindings)
    {
        $component = new RawColumn;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addComponent(ComponentType::GroupBy, $component);
    }
}