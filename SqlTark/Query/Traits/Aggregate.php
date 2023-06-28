<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AggregateClause;
use SqlTark\Expressions\AbstractExpression;

trait Aggregate
{
    /**
     * @param string $type Aggregate type
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAggregate(string $type, $column)
    {
        $column = Helper::resolveExpression($column, true);

        $this->setMethod(MethodType::Aggregate);

        $component = new AggregateClause;
        $component->setType($type);
        $component->setColumn($column);

        return $this->addOrReplaceComponent(ComponentType::Aggregate, $component);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asCount($column)
    {
        return $this->asAggregate('COUNT', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAvg($column)
    {
        return $this->asAggregate('AVG', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asAverage($column)
    {
        return $this->asAggregate('AVG', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asSum($column)
    {
        return $this->asAggregate('SUM', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asMax($column)
    {
        return $this->asAggregate('MAX', $column);
    }

    /**
     * @param scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return static Self object
     */
    public function asMin($column)
    {
        return $this->asAggregate('MIN', $column);
    }
}