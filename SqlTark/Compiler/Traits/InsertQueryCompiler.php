<?php

declare(strict_types=1);

namespace SqlTark\Compiler\Traits;

use SqlTark\Query;
use InvalidArgumentException;
use SqlTark\Component\InsertClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\InsertQueryClause;

trait InsertQueryCompiler
{
    use ExpressionCompiler;

    /**
     * {@inheritdoc}
     */
    public function compileInsertQuery(Query $query): string
    {
        $values = $query->getOneComponent(ComponentType::Insert);
        if(empty($values)) {
            throw new InvalidArgumentException(
                "Insert query does not have value"
            );
        }

        $fromComponent = $query->getOneComponent(ComponentType::From);
        $resolvedTable  = $this->compileFrom($fromComponent);
        if(empty($from)) {
            throw new InvalidArgumentException(
                "Insert query does not have table reference"
            );
        }

        $result = "INSERT INTO {$resolvedTable} ";

        if($values instanceof InsertClause) {
            $result .= '(' . join(', ', array_map(
                function($value) { return $this->wrapIdentifier($value); },
                $values->getColumns()))
            . ') VALUES ';

            $valuesPart = '';
            foreach ($values->getValues() as $row) {
                if ($valuesPart)
                    $valuesPart .= ', ';

                $valuesPart .= '(' . join(', ', array_map(
                    function($value) { return $this->compileExpression($value, false); },
                    $row))
                . ')';
            }
            $result .= $valuesPart;
        }

        elseif($values instanceof InsertQueryClause) {
            $columns = $values->getColumns();
            if(!empty($columns)) {
                $result .= '(' . join(', ', array_map(
                    function($column) { return $this->wrapIdentifier($column); },
                    $columns))
                . ')';
            }

            $query = $values->getQuery();
            $result .= $this->compileQuery($query);
        }

        return $result;
    }
}