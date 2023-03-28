<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Utilities\Helper;
use SqlTark\Component\RawFrom;
use SqlTark\Component\FromClause;
use SqlTark\Component\InsertClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\InsertQueryClause;

trait InsertQueryCompiler
{
    /**
     * {@inheritdoc}
     */
    public function compileInsertQuery(Query $query): string
    {
        $from = $query->getOneComponent(ComponentType::From);
        if(empty($from)) {
            throw new InvalidArgumentException(
                "Insert query does not have table reference"
            );
        }

        $values = $query->getOneComponent(ComponentType::Insert);
        if(empty($values)) {
            throw new InvalidArgumentException(
                "Insert query does not have value"
            );
        }

        $resolvedTable = null;
        if($from instanceof FromClause) {
            $table = $from->getTable();
            $resolvedTable = $this->wrapIdentifier($table);
        }

        elseif($from instanceof RawFrom) {
            $resolvedTable = $this->compileRaw(
                $from->getExpression(),
                $from->getBindings(),
            );
        }

        else {
            $class = Helper::getType($from);
            throw new InvalidArgumentException(
                "Could not resolve '{$class}' for insert query"
            );
        }

        $result = "INSERT INTO {$resolvedTable} ";

        if($values instanceof InsertClause) {
            $result .= '(' . join(', ', array_map(function($value) { return $this->wrapIdentifier($value); }, $values->getColumns())) . ') VALUES ';

            $valuesPart = '';
            foreach ($values->getValues() as $row) {
                if ($valuesPart)
                    $valuesPart .= ', ';

                $valuesPart .= '(' . join(', ', array_map(function($value) { return $this->compileExpression($value, false); }, $row)) . ')';
            }
            $result .= $valuesPart;
        }

        elseif($values instanceof InsertQueryClause) {
            $columns = $values->getColumns();
            if(!empty($columns)) {
                $result .= '(' . join(', ', array_map(function($column) {return $this->wrapIdentifier($column); }, $columns)) . ')';
            }

            $query = $values->getQuery();
            $result .= $this->compileQuery($query);
        }

        return $result;
    }
}