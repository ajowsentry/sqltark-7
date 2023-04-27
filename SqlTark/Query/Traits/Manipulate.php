<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Component\FromClause;
use SqlTark\Query;
use DateTimeInterface;
use SqlTark\Query\MethodType;
use SqlTark\Utilities\Helper;
use SqlTark\Component\InsertClause;
use SqlTark\Component\ComponentType;
use SqlTark\Component\InsertQueryClause;
use SqlTark\Component\UpdateClause;
use SqlTark\Expressions\AbstractExpression;

trait Manipulate
{
    /**
     * @return $this Self object
     */
    public function asDelete(string ...$tables)
    {
        $this->method = MethodType::Delete;

        foreach($tables as $table) {
            $component = new FromClause;
            $component->setTable($table);
            $this->addComponent(ComponentType::From, $component);
        }

        return $this;
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return $this Self object
     */
    public function asInsert(array $keyValues)
    {
        $this->method = MethodType::Insert;

        $component = new InsertClause;
        $component->setColumns(array_keys($keyValues));
        $component->setValues([Helper::resolveExpressionList(array_values($keyValues), false)]);

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param list<string> $columns
     * @param list<list<AbstractExpression|Query>> $values
     * @return $this Self object
     */
    public function asBulkInsert(iterable $columns, iterable $values)
    {
        $this->method = MethodType::Insert;

        $component = new InsertClause;
        $component->setColumns($columns);
        $component->setValues(array_map(function($item) { return Helper::resolveExpressionList($item); }, $values));

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param (\Closure(Query):void)|Query $query
     * @param ?list<string> $columns
     * @return $this Self object
     */
    public function asInsertWith($query, ?iterable $columns = null)
    {
        $this->method = MethodType::Insert;
        
        $component = new InsertQueryClause;
        $component->setQuery($query);
        $component->setColumns($columns);

        return $this->addOrReplaceComponent(ComponentType::Insert, $component);
    }

    /**
     * @param array<string,null|scalar|DateTimeInterface|AbstractExpression|Query> $keyValues
     * @return $this Self object
     */
    public function asUpdate(array $keyValues)
    {
        $this->method = MethodType::Update;

        $component = new UpdateClause;
        $component->setValues(Helper::resolveExpressionList($keyValues, false));

        return $this->addOrReplaceComponent(ComponentType::Update, $component);
    }
}