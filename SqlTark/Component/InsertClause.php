<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class InsertClause extends AbstractFrom
{
    /**
     * @var list<string> $columns
     */
    protected $columns;

    /**
     * @var list<list<AbstractExpression|Query>> $values
     */
    protected $values;

    /**
     * @return list<string>
     */
    public function getColumns(): iterable
    {
        return $this->columns;
    }

    /**
     * @param list<string> $value
     * @return void
     */
    public function setColumns(iterable $value): void
    {
        $this->columns = $value;
    }

    /**
     * @return list<list<AbstractExpression|Query>>
     */
    public function getValues(): iterable
    {
        return $this->values;
    }

    /**
     * @param list<list<AbstractExpression|Query>> $value
     * @return void
     */
    public function setValues(iterable $value): void
    {
        $this->values = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->columns = Helper::clone($this->columns);
        $this->values = Helper::clone($this->values);
    }
}
