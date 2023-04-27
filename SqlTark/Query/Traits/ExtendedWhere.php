<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use InvalidArgumentException;
use SqlTark\Component\ComponentType;

/**
 * @method $this where(null|scalar|DateTimeInterface|AbstractExpression|Query $left, string $operator, null|scalar|DateTimeInterface|AbstractExpression|Query $right)
 * @method $this where(null|scalar|DateTimeInterface|AbstractExpression|Query $left, null|scalar|DateTimeInterface|AbstractExpression|Query $right)
 * @method $this orWhere(null|scalar|DateTimeInterface|AbstractExpression|Query $left, string $operator, null|scalar|DateTimeInterface|AbstractExpression|Query $right)
 * @method $this orWhere(null|scalar|DateTimeInterface|AbstractExpression|Query $left, null|scalar|DateTimeInterface|AbstractExpression|Query $right)
 */
trait ExtendedWhere
{
    /**
     * @return $this Self object
     */
    public function where()
    {
        $this->conditionComponent = ComponentType::Where;

        $numArgs = func_num_args();
        if($numArgs === 3) {
            return call_user_func_array([$this, 'compare'], func_get_args());
        }
        elseif($numArgs === 2) {
            return call_user_func_array([$this, 'equals'], func_get_args());
        }

        throw new InvalidArgumentException("Invalid number of arguments for method 'where'");
    }

    /**
     * @return $this Self object
     */
    public function orWhere()
    {
        return call_user_func_array([$this->or(), 'where'], func_get_args());
    }
}