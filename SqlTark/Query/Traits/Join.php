<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use DateTimeInterface;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\JoinType;
use SqlTark\Component\JoinClause;
use SqlTark\Component\ComponentType;
use SqlTark\Query\Join as QueryJoin;
use SqlTark\Expressions\AbstractExpression;

trait Join
{
    /**
     * @param (\Closure(QueryJoin):void)|QueryJoin $join
     * @param ?int $type JoinType enum
     * @return static Self object
     */
    public function joinWith($join, ?int $type = null)
    {
        $join = Helper::resolveJoin($join, $this);

        if(! is_null($type))
            $join->asType($type);
        
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @param int $type JoinType enum
     * @return static Self object
     */
    public function join($table, $left, string $operator, $right, int $type = JoinType::Join)
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType($type)->on($left, $operator, $right);
        
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function innerJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::InnerJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function leftJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::LeftJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function rightJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::RightJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function outerJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::OuterJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function leftOuterJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::LeftOuterJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function rightOuterJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::RightOuterJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function fullOuterJoin($table, $left, string $operator, $right)
    {
        return $this->join($table, $left, $operator, $right, JoinType::FullOuterJoin);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @return static Self object
     */
    public function crossJoin($table)
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType(JoinType::CrossJoin);
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }

    /**
     * @param string|(\Closure(Query):void)|Query $table
     * @return static Self object
     */
    public function naturalJoin($table)
    {
        $join = new QueryJoin;
        is_string($table) ? $join->from($table) : $join->fromQuery($table);

        $join->asType(JoinType::NaturalJoin);
        $component = new JoinClause;
        $component->setJoin($join);

        return $this->addComponent(ComponentType::Join, $component);
    }
}