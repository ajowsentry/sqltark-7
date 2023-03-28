<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Utilities\Helper;
use SqlTark\Expressions\Column;
use SqlTark\Expressions\Literal;
use SqlTark\Expressions\Variable;

trait ExpressionCompiler
{
    /**
     * {@inheritdoc}
     */
    public function compileLiteral(Literal $literal, bool $withAlias = true): string
    {
        $value = $literal->getValue();
        $result = $this->wrapFunction($this->quote($value), $literal->getWrap());

        if($withAlias && !is_null($alias = $literal->getAlias())) {
            $result .= ' AS ' . $this->wrapIdentifier($alias);
        }
        
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function compileColumn(Column $column, bool $withAlias = true): string
    {
        $result = trim($column->getName());
        if (empty($result) || $result == '*')
            return $this->wrapFunction('*', $column->getWrap());

        $aliasSplit = array_map(function($item) { return $this->wrapIdentifier($item); }, Helper::extractAlias($result));

        $columnExression = $this->wrapFunction($aliasSplit[0], $column->getWrap());
        if ($withAlias && isset($aliasSplit[1]))
            $columnExression .= ' AS ' . $aliasSplit[1];

        return $columnExression;
    }

    /**
     * {@inheritdoc}
     */
    public function compileRaw(string $expression, iterable $bindings = []): string
    {
        return Helper::replaceAll(
            trim($expression, " \t\n\r\0\x0B,"),
            $this->parameterPlaceholder,
            function($index) use ($bindings) {
                return $this->compileExpression($bindings[$index], false);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function compileVariable(Variable $variable, bool $withAlias = true): string
    {
        if(is_null($variable->getName())) {
            $result = $this->wrapFunction($this->parameterPlaceholder, $variable->getWrap());
        }
        else {
            $result = trim($variable->getName());

            if (isset($result[0]) && $result[0] != $this->variablePrefix)
                $result = $this->variablePrefix . $result;

            $result = $this->wrapFunction($result, $variable->getWrap());
        }

        if($withAlias && !is_null($alias = $variable->getAlias()))
            $result .= ' AS ' . $this->wrapIdentifier($alias);

        return $result;
    }

    /**
     * @param string $value
     * @param ?string $wrapper
     * @return string
     */
    protected function wrapFunction(string $value, ?string $wrapper): string
    {
        return !is_null($wrapper) ? "{$wrapper}({$value})" : $value;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function wrapIdentifier(string $value): string
    {
        return join('.', array_reduce(preg_split('/\s*\.\s*/', $value, -1, PREG_SPLIT_NO_EMPTY), function($acc, $item) {
            if ($item != '*' && $item[0] != $this->openingIdentifier)
                $item = $this->openingIdentifier . $item;

            if ($item != '*' && $item[strlen($item) - 1] != $this->closingIdentifier)
                $item = $item . $this->closingIdentifier;

            array_push($acc, $item);

            return $acc;
        }, []));
    }
}