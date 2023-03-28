<?php

declare(strict_types=1);

namespace SqlTark\Compiler;

use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Expressions\Raw;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;
use SqlTark\Expressions\AbstractExpression;

abstract class AbstractCompiler
{
    /**
     * @var string $openingIdentifier
     */
    protected $openingIdentifier;

    /**
     * @var string $closingIdentifier
     */
    protected $closingIdentifier;

    /**
     * @var string $parameterPlaceholder
     */
    protected $parameterPlaceholder = '?';

    /**
     * @var string $variablePrefix
     */
    protected $variablePrefix = ':';

    /**
     * @var string $escapeCharacter
     */
    protected $escapeCharacter = '\\';

    /**
     * @var string $dummyTable
     */
    protected $dummyTable = null;

    /**
     * @var bool $fromTableRequired
     */
    protected $fromTableRequired = false;

    /**
     * @var string $maxValue
     */
    protected $maxValue = '18446744073709551615';

    /**
     * @param AbstractExpression|Query $expression
     * @return string
     */
    public function compileExpression($expression, bool $withAlias = true): string
    {
        if($expression instanceof Literal)
            return $this->compileLiteral($expression, $withAlias);

        if($expression instanceof Column)
            return $this->compileColumn($expression, $withAlias);

        if($expression instanceof Raw)
            return $this->compileRaw($expression->getExpression(), $expression->getBindings());

        if($expression instanceof Variable)
            return $this->compileVariable($expression, $withAlias);

        if($expression instanceof Query) {
            $alias = $withAlias && $expression->getAlias()
                ? (' AS ' . $this->wrapIdentifier($expression->getAlias()))
                : '';

            return '(' . $this->compileQuery($expression) . ')' . $alias;
        }

        Helper::throwInvalidArgumentException(
            "Could not resolve expression from '%s' type.",
            $expression
        );
    }

    /**
     * @param Query $query
     * @return string
     */
    public function compileQuery(Query $query): string
    {
        switch($query->getMethod()) {
            case MethodType::Select:
                return $this->compileSelectQuery($query);
            case MethodType::Update:
                return $this->compileUpdateQuery($query);
            case MethodType::Insert:
                return $this->compileInsertQuery($query);
            case MethodType::Delete:
                return $this->compileDeleteQuery($query);
        }

        Helper::throwInvalidArgumentException(
            "Unable to compile query with %s method.",
            $query->getMethod()
        );
    }

    /**
     * @param Literal $literal
     * @param bool $withAlias
     * @return string
     */
    public abstract function compileLiteral(Literal $literal, bool $withAlias = true): string;

    /**
     * @param Column $column
     * @return string
     */
    public abstract function compileColumn(Column $column, bool $withAlias = true): string;

    /**
     * @param string $expression
     * @param list<AbstractExpression|Query> $bindings
     * @return string
     */
    public abstract function compileRaw(string $expression, iterable $bindings = []): string;

    /**
     * @param Variable $variable
     * @return string
     */
    public abstract function compileVariable(Variable $variable, bool $withAlias = true): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileSelectQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileInsertQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileUpdateQuery(Query $query): string;

    /**
     * @param Query $query
     * @return string
     */
    public abstract function compileDeleteQuery(Query $query): string;

    /**
     * @param null|scalar|DateTimeInterface $value
     * @param bool $quoteLike
     * @return string
     */
    public abstract function quote($value, bool $quoteLike = false): string;

    /**
     * @param string $value
     * @return string
     */
    protected abstract function wrapIdentifier(string $value): string;
}