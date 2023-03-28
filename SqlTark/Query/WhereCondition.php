<?php

declare(strict_types=1);

namespace SqlTark\Query;

class WhereCondition extends AbstractQuery implements ConditionInterface
{
    use Traits\Condition;
}