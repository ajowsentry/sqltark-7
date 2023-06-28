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

trait Select
{
    /**
     * @var bool $distinct
     */
    protected $distinct = false;

    /**
     * @return bool
     */
    public function isDistict(): bool
    {
        return $this->distinct;
    }

    /**
     * @param bool $value
     * @return static Self object
     */
    public function distinct(bool $value = true)
    {
        $this->distinct = $value;
        return $this;
    }

    /**
     * @param null|scalar|AbstractExpression|Query ...$columns
     * @return static Self object
     */
    public function select(...$columns)
    {
        foreach ($columns as $column) {
            $column = Helper::resolveExpression($column, true);

            $component = new ColumnClause;
            $component->setColumn($column);

            $this->addComponent(ComponentType::Select, $component);
        }

        return $this;
    }

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression ...$bindings
     * @return static Self object
     */
    public function selectRaw(string $expression, ...$bindings)
    {
        $component = new RawColumn;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addComponent(ComponentType::Select, $component);
    }
}
