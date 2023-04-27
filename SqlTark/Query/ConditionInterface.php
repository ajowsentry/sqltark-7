<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Component\LikeType;
use SqlTark\Expressions\AbstractExpression;

interface ConditionInterface extends QueryInterface
{
    /**
     * @return $this Self object
     */
    public function or();

    /**
     * @return $this Self object
     */
    public function and();

    /**
     * @param bool $value
     * @return $this Self object
     */
    public function not(bool $value = true);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function compare($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param string $operator
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orCompare($left, string $operator, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function equals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function notEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orNotEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function greaterThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orGreaterThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function greaterEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orGreaterEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function lesserThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orLesserThan($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function lesserEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $left
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $right
     * @return $this Self object
     */
    public function orLesserEquals($left, $right);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query>|Query $list
     * @return $this Self object
     */
    public function in($column, $list);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query>|Query $list
     * @return $this Self object
     */
    public function orIn($column, $list);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query>|Query $list
     * @return $this Self object
     */
    public function notIn($column, $list);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param list<null|scalar|DateTimeInterface|AbstractExpression|Query>|Query $list
     * @return $this Self object
     */
    public function orNotIn($column, $list);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return $this Self object
     */
    public function isNull($column);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return $this Self object
     */
    public function orIsNull($column);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return $this Self object
     */
    public function notIsNull($column);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @return $this Self object
     */
    public function orNotIsNull($column);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return $this Self object
     */
    public function between($column, $low, $high);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return $this Self object
     */
    public function orBetween($column, $low, $high);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return $this Self object
     */
    public function notBetween($column, $low, $high);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $low
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $high
     * @return $this Self object
     */
    public function orNotBetween($column, $low, $high);

    /**
     * @param (\Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return $this Self object
     */
    public function group($condition);

    /**
     * @param (\Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return $this Self object
     */
    public function orGroup($condition);

    /**
     * @param (\Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return $this Self object
     */
    public function notGroup($condition);

    /**
     * @param (\Closure(ConditionInterface):void)|ConditionInterface $condition
     * @return $this Self object
     */
    public function orNotGroup($condition);

    /**
     * @param (\Closure(Query):void)|Query $query
     * @return $this Self object
     */
    public function exists($query);

    /**
     * @param (\Closure(Query):void)|Query $query
     * @return $this Self object
     */
    public function orExists($query);

    /**
     * @param (\Closure(Query):void)|Query $query
     * @return $this Self object
     */
    public function notExists($query);

    /**
     * @param (\Closure(Query):void)|Query $query
     * @return $this Self object
     */
    public function orNotExists($query);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @param int $likeType LikeType enum
     * @return $this Self object
     */
    public function like($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null, int $likeType = LikeType::Like);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function notLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orNotLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function startsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function notStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orNotStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function endsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function notEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orNotEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function contains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function notContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query $column
     * @param string $value
     * @param bool $caseSensitive
     * @param ?string $escapeCharacter
     * @return $this Self object
     */
    public function orNotContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null);

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return $this Self object
     */
    public function conditionRaw(string $expression, ...$bindings);

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return $this Self object
     */
    public function orConditionRaw(string $expression, ...$bindings);

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return $this Self object
     */
    public function notConditionRaw(string $expression, ...$bindings);

    /**
     * @param string $expression
     * @param null|scalar|DateTimeInterface|AbstractExpression|Query ...$bindings
     * @return $this Self object
     */
    public function orNotConditionRaw(string $expression, ...$bindings);
}