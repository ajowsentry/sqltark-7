<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Utilities\Helper;

trait JoinCondition
{
    use Condition;

    /**
     * {@inheritdoc}
     */
    public function on($left, string $operator, $right)
    {
        return $this->compare($left, $operator, Helper::resolveExpression($right, true));
    }

    /**
     * {@inheritdoc}
     */
    public function orOn($left, string $operator, $right)
    {
        return $this->or()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notOn($left, string $operator, $right)
    {
        return $this->not()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotOn($left, string $operator, $right)
    {
        return $this->or()->not()->on($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onEquals($left, $right)
    {
        return $this->on($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnEquals($left, $right)
    {
        return $this->or()->on($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notOnEquals($left, $right)
    {
        return $this->on($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotOnEquals($left, $right)
    {
        return $this->or()->on($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onGreaterThan($left, $right)
    {
        return $this->on($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnGreaterThan($left, $right)
    {
        return $this->or()->on($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onGreaterEquals($left, $right)
    {
        return $this->on($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnGreaterEquals($left, $right)
    {
        return $this->or()->on($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onLesserThan($left, $right)
    {
        return $this->on($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnLesserThan($left, $right)
    {
        return $this->or()->on($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function onLesserEquals($left, $right)
    {
        return $this->on($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orOnLesserEquals($left, $right)
    {
        return $this->or()->on($left, '<=', $right);
    }
}