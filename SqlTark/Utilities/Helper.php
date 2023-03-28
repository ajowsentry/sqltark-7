<?php

declare(strict_types=1);

namespace SqlTark\Utilities;

use Closure;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Query\Join;
use SqlTark\Expressions;
use InvalidArgumentException;
use SqlTark\Query\AbstractQuery;
use SqlTark\Query\WhereCondition;
use SqlTark\Query\HavingCondition;
use SqlTark\Component\ComponentType;
use SqlTark\Query\ConditionInterface;
use SqlTark\Expressions\AbstractExpression;

final class Helper
{
    /**
     * @param mixed $expression
     * @param bool $stringAsColumn
     * @return Query|AbstractExpression
     */
    public static function resolveExpression($expression, bool $stringAsColumn = false)
    {
        if ($expression instanceof AbstractExpression) {
            return $expression;
        }

        elseif ($expression instanceof Query) {
            return clone $expression;
        }

        elseif (is_string($expression) && $stringAsColumn) {
            return Expressions::column($expression);
        }

        elseif (is_null($expression) || is_scalar($expression) || $expression instanceof DateTimeInterface) {
            return Expressions::literal($expression);
        }

        self::throwInvalidArgumentException(
            "Could not resolve expression from type '%s'",
            $expression
        );
    }

    /**
     * @template T of string|int
     * @param array<T,mixed> $expressionList
     * @param bool $stringAsColumn
     * @return array<T,Query|AbstractExpression>
     */
    public static function resolveExpressionList($expressionList, bool $stringAsColumn = false): array
    {
        return array_map(function($item) use ($stringAsColumn) {
            return self::resolveExpression($item, $stringAsColumn);
        }, $expressionList);
    }

    /**
     * @param (Closure(Query):void)|Query $value
     * @param AbstractQuery $query
     * @return Query
     */
    public static function resolveQuery($value, AbstractQuery $query): Query
    {
        if($value instanceof Closure) {
            $child = $query->newChild();
            $value($child);
            return $child;
        }

        return clone $value;
    }

    /**
     * @param (Closure(Join):void)|Join $value
     * @param AbstractQuery $query
     * @return Join
     */
    public static function resolveJoin($value, AbstractQuery $query): Join
    {
        if($value instanceof Closure) {
            $child = new Join;

            $child->setParent($query);
            $value($child);

            return $child;
        }

        return clone $value;
    }

    /**
     * @param (Closure(ConditionInterface):void)|ConditionInterface $value
     * @param AbstractQuery $query
     * @return ConditionInterface
     */
    public static function resolveCondition($value, AbstractQuery $query): ConditionInterface
    {
        if($value instanceof Closure) {
            $condition = $query->getConditionComponent() == ComponentType::Having
                ? new HavingCondition
                : new WhereCondition;

            $condition->setParent($query);
            $value($condition);

            return $condition;
        }

        return clone $value;
    }

    /**
     * @param ?string $subject
     * @param string $match
     * @param Closure $callback
     * @return string
     */
    public static function replaceAll(?string $subject, string $match, Closure $callback): string
    {
        if (empty($subject) || strpos($subject, $match) === false) {
            return (string) $subject;
        }

        $splitted = explode($match, $subject);

        $splitProcess = [];
        for ($i = 1; $i < count($splitted); $i++) {
            $splitProcess[] = $callback($i - 1) . $splitted[$i];
        }

        $result = array_reduce($splitProcess, function($acc, $item) { return $acc . $item; }, $splitted[0]);

        return $result;
    }

    /**
     * @param string $expression
     * @return list<string>
     */
    public static function extractAlias(string $expression): array
    {
        return preg_split('/\s+as\s+/i', trim($expression), 2, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function getType($value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    /**
     * @template T
     * @param T $value
     * @return T
     */
    public static function clone($value)
    {
        return is_object($value) ? clone $value : $value;
    }

    /**
     * @param string $message
     * @param mixed  $object
     * @return never
     */
    public static function throwInvalidArgumentException(string $message, $object)
    {
        $type = self::getType($object);
        throw new InvalidArgumentException(sprintf($message, $type));
    }

    /**
     * @param array<mixed,mixed> $list
     * @return list<mixed>
     */
    public static function flatten($list)
    {
        $result = [];

        foreach ($list as $item) is_iterable($item)
            ? array_push($result, ...self::flatten($item))
            : array_push($result, $item);

        return $result;
    }

    /**
     * @return bool
     */
    public static function isOSWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function __construct() { }
}