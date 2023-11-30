<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;

class FromClause extends AbstractFrom
{
    /**
     * @var string|Query $table
     */
    protected $table;

    /**
     * @return string|Query
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string|Query $value
     * @return void
     */
    public function setTable($value): void
    {
        $this->table = $value;
    }

    public function getAlias(): string
    {
        if (empty($this->alias) && is_string($this->table)) {
            $pair = Helper::extractAlias($this->table);
            return $pair[1] ?? $pair[0];
        }

        elseif ($this->table instanceof Query && !is_null($alias = $this->table->getAlias())) {
            return $alias;
        }

        return $this->alias ?? '';
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->table = Helper::clone($this->table);
    }
}
