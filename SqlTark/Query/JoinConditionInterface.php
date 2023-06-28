<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Expressions\AbstractExpression;

interface JoinConditionInterface extends ConditionInterface
{
    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function on($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOn($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function notOn($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orNotOn($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function notOnEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orNotOnEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onGreaterThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnGreaterThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onGreaterEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnGreaterEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onLesserThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnLesserThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function onLesserEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return static Self object
     */
    public function orOnLesserEquals($left, $right);

}