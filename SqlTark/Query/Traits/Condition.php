<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\LikeType;

trait Condition
{
    use BaseCondition;

    /**
     * {@inheritdoc}
     */
    public function orCompare($left, string $operator, $right)
    {
        return $this->or()->compare($left, $operator, $right);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($left, $right)
    {
        return $this->compare($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orEquals($left, $right)
    {
        return $this->or()->compare($left, '=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function notEquals($left, $right)
    {
        return $this->compare($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotEquals($left, $right)
    {
        return $this->or()->compare($left, '!=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterThan($left, $right)
    {
        return $this->compare($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orGreaterThan($left, $right)
    {
        return $this->or()->compare($left, '>', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function greaterEquals($left, $right)
    {
        return $this->compare($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orGreaterEquals($left, $right)
    {
        return $this->or()->compare($left, '>=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function lesserThan($left, $right)
    {
        return $this->compare($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orLesserThan($left, $right)
    {
        return $this->or()->compare($left, '<', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function lesserEquals($left, $right)
    {
        return $this->compare($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orLesserEquals($left, $right)
    {
        return $this->or()->compare($left, '<=', $right);
    }

    /**
     * {@inheritdoc}
     */
    public function orIn($column, $list)
    {
        return $this->or()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function notIn($column, $list)
    {
        return $this->not()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotIn($column, $list)
    {
        return $this->or()->not()->in($column, $list);
    }

    /**
     * {@inheritdoc}
     */
    public function orIsNull($column)
    {
        return $this->or()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function notIsNull($column)
    {
        return $this->not()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotIsNull($column)
    {
        return $this->or()->not()->isNull($column);
    }

    /**
     * {@inheritdoc}
     */
    public function orBetween($column, $low, $high)
    {
        return $this->or()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function notBetween($column, $low, $high)
    {
        return $this->not()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotBetween($column, $low, $high)
    {
        return $this->or()->not()->between($column, $low, $high);
    }

    /**
     * {@inheritdoc}
     */
    public function orGroup($condition)
    {
        return $this->or()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function notGroup($condition)
    {
        return $this->not()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotGroup($condition)
    {
        return $this->or()->not()->group($condition);
    }

    /**
     * {@inheritdoc}
     */
    public function orExists($query)
    {
        return $this->or()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function notExists($query)
    {
        return $this->not()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotExists($query)
    {
        return $this->or()->not()->exists($query);
    }

    /**
     * {@inheritdoc}
     */
    public function orLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->not()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotLike($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->not()->like($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function startsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Starts);
    }

    /**
     * {@inheritdoc}
     */
    public function orStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->not()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotStartsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->not()->startsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function endsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Ends);
    }

    /**
     * {@inheritdoc}
     */
    public function orEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->not()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotEndsWith($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->not()->endsWith($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->like($column, $value, $caseSensitive, $escapeCharacter, LikeType::Contains);
    }

    /**
     * {@inheritdoc}
     */
    public function orContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function notContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->not()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotContains($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null)
    {
        return $this->or()->not()->contains($column, $value, $caseSensitive, $escapeCharacter);
    }

    /**
     * {@inheritdoc}
     */
    public function orConditionRaw(string $expression, ...$bindings)
    {
        return $this->or()->conditionRaw($expression, ...$bindings);
    }

    /**
     * {@inheritdoc}
     */
    public function notConditionRaw(string $expression, ...$bindings)
    {
        return $this->not()->conditionRaw($expression, ...$bindings);
    }

    /**
     * {@inheritdoc}
     */
    public function orNotConditionRaw(string $expression, ...$bindings)
    {
        return $this->or()->not()->conditionRaw($expression, ...$bindings);
    }
}