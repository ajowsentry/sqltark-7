<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class UpdateClause extends AbstractFrom
{
    /**
     * @var array<string,AbstractExpression|Query> $values
     */
    protected $values;

    /**
     * @return array<string,AbstractExpression|Query>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array<string,AbstractExpression|Query> $values
     * @return void
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->values = Helper::clone($this->values);
    }
}
