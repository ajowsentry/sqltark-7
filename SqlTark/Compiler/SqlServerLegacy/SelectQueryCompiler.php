<?php

declare(strict_types=1);

namespace SqlTark\Compiler\SqlServerLegacy;

use SqlTark\Query;
use SqlTark\Expressions;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawFrom;
use SqlTark\Component\JoinType;
use SqlTark\Component\LikeType;
use SqlTark\Component\RawOrder;
use SqlTark\Component\RawColumn;
use SqlTark\Component\FromClause;
use SqlTark\Component\JoinClause;
use SqlTark\Component\InCondition;
use SqlTark\Component\LimitClause;
use SqlTark\Component\OrderClause;
use SqlTark\Component\RandomOrder;
use SqlTark\Component\AbstractFrom;
use SqlTark\Component\AbstractJoin;
use SqlTark\Component\ColumnClause;
use SqlTark\Component\OffsetClause;
use SqlTark\Component\RawCondition;
use SqlTark\Component\AbstractOrder;
use SqlTark\Component\CombineClause;
use SqlTark\Component\CompareClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\LikeCondition;
use SqlTark\Component\NullCondition;
use SqlTark\Component\AbstractColumn;
use SqlTark\Component\GroupCondition;
use SqlTark\Component\ExistsCondition;
use SqlTark\Component\BetweenCondition;
use SqlTark\Component\AbstractCondition;
use SqlTark\Component\CombineType;

trait SelectQueryCompiler
{
    /**
     * {@inheritdoc}
     */
    public function compileSelectQuery(Query $query): string
    {
        /** @var list<AbstractFrom> $cte */
        $cte = $query->getComponents(ComponentType::CTE);

        /** @var list<AbstractColumn> $selects */
        $selects = $query->getComponents(ComponentType::Select);

        /** @var ?AbstractFrom $from */
        $from = $query->getOneComponent(ComponentType::From);

        /** @var list<AbstractJoin> $joins */
        $joins = $query->getComponents(ComponentType::Join);

        /** @var list<AbstractCondition> $where */
        $where = $query->getComponents(ComponentType::Where);

        /** @var list<AbstractColumn> $groupBy */
        $groupBy = $query->getComponents(ComponentType::GroupBy);

        /** @var list<AbstractCondition> $havings */
        $havings = $query->getComponents(ComponentType::Having);

        /** @var list<AbstractOrder> $orderBy */
        $orderBy = $query->getComponents(ComponentType::OrderBy);

        /** @var list<CombineClause> $combines */
        $combines = $query->getComponents(ComponentType::Combine);

        /** @var ?LimitClause $limit */
        $limit = $query->getOneComponent(ComponentType::Limit);

        /** @var ?OffsetClause $offset */
        $offset = $query->getOneComponent(ComponentType::Offset);


        $result = '';

        $resolvedCte = $this->compileCte($cte);
        if($resolvedCte) {
            $result .= $resolvedCte . ' ';
        }

        if(!is_null($offset) && $offset->hasOffset() && empty($orderBy)) {
            if(empty($selects)) {
                $selectComponent = new ColumnClause;
                $selectComponent->setColumn(Expressions::column('*'));
                array_push($selects, $selectComponent);
            }

            $selectComponent = new ColumnClause;
            $selectComponent->setColumn(Expressions::literal(0)->as('__order__'));
            array_push($selects, $selectComponent);
        }

        $result .= $this->compileSelect(
            $selects,
            $query->isDistict(),
            (is_null($offset) || !$offset->hasOffset()) && (is_null($limit) || !$limit->hasLimit())
                ? $limit
                : null
        );

        $resolvedFrom = $this->compileFrom($from);
        if($resolvedFrom) {
            $result .= ' FROM ' . $resolvedFrom;
        }

        $resolvedJoin = $this->compileJoin($joins);
        if($resolvedJoin) {
            $result .= ' ' . $resolvedJoin;
        }

        $resolvedWhere = $this->compileWhere($where);
        if($resolvedWhere) {
            $result .= ' ' . $resolvedWhere;
        }

        $resolvedGroupBy = $this->compileGroupBy($groupBy);
        if($resolvedGroupBy) {
            $result .= ' ' . $resolvedGroupBy;
        }

        $resolvedHaving = $this->compileHaving($havings);
        if($resolvedHaving) {
            $result .= ' ' . $resolvedHaving;
        }

        $resolvedOrderBy = $this->compileOrderBy($orderBy);
        if($resolvedOrderBy && (is_null($offset) || !$offset->hasOffset())) {
            $result .= ' ' . $resolvedOrderBy;
        }

        $resolvedCombine = $this->compileCombine($combines);
        if($resolvedCombine) {
            $result .= ' ' . $resolvedCombine;
        }

        if(!is_null($offset) && $offset->hasOffset()) {
            $resolvedResult = 'WITH ' . $this->wrapIdentifier('__main__') . ' AS (' . $result . '), ';

            if(!$resolvedOrderBy) {
                $resolvedOrderBy = 'ORDER BY ' . $this->wrapIdentifier('__order__');
            }

            $resolvedResult .= $this->wrapIdentifier('__paging__')
                . ' AS (SELECT *, ROW_NUMBER() OVER ('
                . $resolvedOrderBy . ') AS '
                . $this->wrapIdentifier('__rownum__')
                . ' FROM '
                . $this->wrapIdentifier('__main__')
                . ') SELECT ';
            
            if(!is_null($limit) && $limit->hasLimit()) {
                $resolvedResult .= 'TOP ' . $limit->getLimit() . ' ';
            }

            return $resolvedResult . '* FROM ' . $this->wrapIdentifier('__paging__')
                . ' WHERE ' . $this->wrapIdentifier('__rownum__') . ' > ' . $offset->getOffset()
                . ' ORDER BY ' . $this->wrapIdentifier('__rownum__');
        }

        return $result;
    }

    /**
     * @param list<AbstractColumn> $columns
     * @param bool $isDistinct
     * @return string
     */
    protected function compileSelect(iterable $columns, bool $isDistinct, ?LimitClause $limitClause = null): string
    {
        $result = $this->compileColumns($columns, true);
        if (empty($result)) {
            $result = '*';
        }

        if(!is_null($limitClause) && $limitClause->hasLimit()) {
            $limit = $limitClause->getLimit();
            $result = "TOP {$limit} {$result}";
        }

        if($isDistinct) {
            $result = "DISTINCT {$result}";
        }

        return "SELECT {$result}";
    }

    /**
     * @param list<AbstractColumn> $columns
     * @param bool $withAlias
     * @return string
     */
    protected function compileColumns(iterable $columns, bool $withAlias): string
    {
        $result = '';
        foreach ($columns as $column) {
            $resolvedColumn = null;
            if ($column instanceof ColumnClause) {
                $columnContent = $column->getColumn();
                $resolvedColumn = $this->compileExpression($columnContent, $withAlias);
            }

            elseif ($column instanceof RawColumn) {
                $resolvedColumn = $this->compileRaw(
                    $column->getExpression(),
                    $column->getBindings()
                );
            }

            if (! is_null($resolvedColumn)) {
                if ($result) $result .= ', ';
                $result .= $resolvedColumn;
            }
        }

        return $result;
    }

    /**
     * @param ?AbstractFrom $table
     * @return string
     */
    protected function compileFrom(?AbstractFrom $table): string
    {
        $result = '';

        if ($table instanceof FromClause) {
            $expression = $table->getTable();
            if (is_string($expression)) {
                $result = $this->compileTable($expression);
            } elseif ($expression instanceof Query) {
                $result = '(' . $this->compileQuery($expression) . ') AS ' . $this->wrapIdentifier($table->getAlias());
            }
        }
        elseif ($table instanceof RawFrom) {
            $result = $this->compileRaw(
                $table->getExpression(),
                $table->getBindings()
            );
        }

        if (empty($result) && $this->fromTableRequired) {
            $result = $this->dummyTable;
        }

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function compileTable(string $name): string
    {
        $result = trim($name);
        if (empty($result)) {
            return '';
        }

        $aliasSplit = array_map(
            function($item) { return $this->wrapIdentifier($item); },
            Helper::extractAlias($result)
        );

        $result = $aliasSplit[0];
        if (isset($aliasSplit[1])) {
            $result .= ' AS ' . $aliasSplit[1];
        }

        return $result;
    }

    /**
     * @param list<AbstractJoin> $joins
     * @return string
     */
    protected function compileJoin(iterable $joins): string
    {
        $result = '';
        foreach ($joins as $component) {
            $resolvedJoin = null;
            if ($component instanceof JoinClause) {
                $join = $component->getJoin();

                $table = $join->getOneComponent(ComponentType::From, AbstractFrom::class);
                $resolvedTable = $this->compileFrom($table);

                $resolvedJoin = JoinType::syntaxOf($join->getType()) . ' ' . $resolvedTable;
                /** Natural and cross join doesn't need on condition */
                if (!in_array($join->getType(), [JoinType::CrossJoin, JoinType::NaturalJoin])) {
                    $conditions = $join->getComponents(ComponentType::Where, AbstractCondition::class);
                    $resolvedCondition = $this->compileConditions($conditions);
                    if (!empty($resolvedCondition)) {
                        $resolvedJoin .= ' ON ' . $resolvedCondition;
                    }
                }
            }

            if (!empty($resolvedJoin)) {
                if ($result) $result .= ' ';
                $result .= $resolvedJoin;
            }
        }

        return $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @param int $type ComponentType enum
     * @return string
     */
    protected function compileConditions(iterable $conditions, int $type = ComponentType::Where): string
    {
        $result = '';
        foreach ($conditions as $condition) {
            $resolvedCondition = null;
            if ($condition instanceof CompareClause) {
                $left = $condition->getLeft();
                $right = $condition->getRight();
                $operator = $condition->getOperator();

                $resolvedLeft = $this->compileExpression($left, false);
                $resolvedRight = $this->compileExpression($right, false);

                $resolvedCondition = "$resolvedLeft $operator $resolvedRight";
                if ($condition->getNot()) {
                    $resolvedCondition = "NOT ({$resolvedCondition})";
                }
            }
            elseif ($condition instanceof BetweenCondition) {
                $column = $condition->getColumn();
                $lower = $condition->getLower();
                $higher = $condition->getHigher();

                $resolvedColumn = $this->compileExpression($column, false);
                $resolvedLower = $this->compileExpression($lower, false);
                $resolvedHigher = $this->compileExpression($higher, false);

                $resolvedCondition = $resolvedColumn;
                if ($condition->getNot()) {
                    $resolvedCondition .= ' NOT';
                }

                $resolvedCondition .= " BETWEEN {$resolvedLower} AND {$resolvedHigher}";
            }
            elseif ($condition instanceof ExistsCondition) {
                $query = $condition->getQuery();
                $resolvedQuery = $this->compileQuery($query);

                $resolvedCondition = $condition->getNot() ? 'NOT EXISTS' : 'EXISTS';
                $resolvedCondition .= "({$resolvedQuery})";
            }
            elseif ($condition instanceof NullCondition) {
                $column = $condition->getColumn();

                $resolvedCondition = $this->compileExpression($column, false);
                $resolvedCondition .= $condition->getNot() ? ' IS NOT NULL' : ' IS NULL';
            }
            elseif ($condition instanceof LikeCondition) {
                $column = $condition->getColumn();
                $resolvedColumn = $this->compileExpression($column, false);

                $value = $condition->getValue();
                $escape = $condition->getEscapeCharacter();

                $operator = $condition->getNot() ? 'NOT LIKE' : 'LIKE';
                if ($condition->isCaseSensitive()) {
                    $operator .= ' BINARY';
                }

                if($condition->getType() != LikeType::Like) {
                    $esc = $escape ?? '\\';
                    $value = str_replace(
                        [$esc, '%', '_'],
                        [$esc . $esc, $esc . '%', $esc . '_'],
                        $value
                    );
                }

                switch ($condition->getType()) {
                    case LikeType::Contains:
                        $value = "%{$value}%";
                        break;
                    case LikeType::Starts:
                        $value = "{$value}%";
                        break;
                    case LikeType::Ends:
                        $value = "%{$value}";
                        break;
                }

                $extraEscape = $condition->getType() != LikeType::Like && (empty($escape) || $escape == '\\');
                $value = $this->quote($value, $extraEscape);

                $resolvedCondition = "{$resolvedColumn} {$operator} {$value}";
                if ($escape) {
                    $resolvedCondition .= " ESCAPE " . $this->quote($escape);
                }
            }
            elseif ($condition instanceof InCondition) {
                $column = $condition->getColumn();
                $values = $condition->getValues();

                $resolvedColumn = $this->compileExpression($column, false);
                $resolvedValues = '';
                if ($values instanceof Query) {
                    $resolvedValues = $this->compileQuery($values);
                }
                else {
                    $resolvedValues .= join(', ', array_map(
                        function($value) { return $this->compileExpression($value, false); },
                        $values
                    ));
                }

                $resolvedCondition = $resolvedColumn;
                $resolvedCondition .= $condition->getNot() ? ' NOT IN ' : ' IN ';
                $resolvedCondition .= "({$resolvedValues})";
            }
            elseif ($condition instanceof GroupCondition) {
                $clauses = $condition->getCondition()->getComponents($type, AbstractCondition::class);
                $resolvedCondition = $this->compileConditions($clauses);
                if(count($clauses) > 1 || (count($clauses) == 1 && $clauses[0] instanceof RawCondition)) {
                    $resolvedCondition = "($resolvedCondition)";
                }
            }
            elseif ($condition instanceof RawCondition) {
                $resolvedCondition = $this->compileRaw(
                    $condition->getExpression(),
                    $condition->getBindings()
                );
            }

            if ($resolvedCondition) {
                if ($result) $result .= $condition->getOr() ? ' OR ' : ' AND ';
                $result .= $resolvedCondition;
            }
        }

        return $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @return string
     */
    protected function compileWhere(iterable $conditions): string
    {
        $resolvedCondition = $this->compileConditions($conditions);
        if (!empty($resolvedCondition)) {
            return 'WHERE ' . $resolvedCondition;
        }

        return '';
    }

    /**
     * @param list<AbstractColumn> $columns
     * @return string
     */
    protected function compileGroupBy(iterable $columns): string
    {
        $result = $this->compileColumns($columns, false);
        if (empty($result)) {
            return '';
        }

        return 'GROUP BY ' . $result;
    }

    /**
     * @param list<AbstractCondition> $conditions
     * @return string
     */
    protected function compileHaving(iterable $conditions): string
    {
        $resolvedCondition = $this->compileConditions($conditions, ComponentType::Having);
        if (!empty($resolvedCondition)) {
            return 'HAVING ' . $resolvedCondition;
        }

        return '';
    }

    /**
     * @param list<AbstractOrder> $columns
     * @return string
     */
    protected function compileOrderBy($columns): string
    {
        $result = '';
        foreach ($columns as $column) {
            $resolvedColumn = null;
            if ($column instanceof OrderClause) {

                $columnContent = $column->getColumn();
                $isAscending = $column->isAscending();
                $resolvedColumn = $this->compileExpression($columnContent, false);
                $resolvedColumn .= $isAscending ? ' ASC' : ' DESC';
            }
            elseif ($column instanceof RawOrder) {

                $resolvedColumn = $this->compileRaw(
                    $column->getExpression(),
                    $column->getBindings()
                );
            }

            elseif ($column instanceof RandomOrder) {
                $resolvedColumn = 'RAND()';
            }

            if (!is_null($resolvedColumn)) {
                if ($result) $result .= ', ';
                $result .= $resolvedColumn;
            }
        }

        if (empty($result)) {
            return '';
        }

        return 'ORDER BY ' . $result;
    }

    /**
     * @param ?LimitClause $limitClause
     * @param ?OffsetClause $offsetClause
     * @return string
     */
    protected function compilePaging(?LimitClause $limitClause, ?OffsetClause $offsetClause): string
    {
        $resolvedPaging = '';

        if(!is_null($offsetClause) && $offsetClause->hasOffset()) {
            $resolvedPaging .= 'OFFSET ' . $offsetClause->getOffset() . ' ROWS';

            if(!is_null($limitClause) && $limitClause->hasLimit()) {
                $resolvedPaging .= ' FETCH NEXT ' . $limitClause->getLimit() . ' ROWS ONLY';
            }
        }

        return $resolvedPaging;
    }

    /**
     * @param list<CombineClause> $combines
     * @return string
     */
    protected function compileCombine(iterable $combines): string
    {
        $result = '';
        foreach($combines as $combine) {
            if($result) $result .= ' ';

            $result .= CombineType::syntaxOf($combine->getOperation());
            if($combine->isAll()) {
                $result .= ' ALL';
            }

            $result .= ' ' . $this->compileQuery($combine->getQuery());
        }

        return $result;
    }

    /**
     * @param list<AbstractFrom> $tables
     * @return string
     */
    protected function compileCte(iterable $tables): string
    {
        $result = '';
        foreach($tables as $fromClause) {
            if($result) $result .= ', ';
            else $result .= 'WITH ';

            $result .= $this->wrapIdentifier($fromClause->getAlias());

            if($fromClause instanceof FromClause) {
                $query = $fromClause->getTable();
                $result .= ' AS (' . $this->compileQuery($query) . ')';
            }
        }

        return $result;
    }
}