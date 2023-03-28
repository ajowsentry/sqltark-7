<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Component\ComponentType;

class HavingCondition extends AbstractQuery implements ConditionInterface
{
    use Traits\Condition;

    /**
     * @var int $conditionComponent ComponentType enum
     */
    protected $conditionComponent = ComponentType::Having;
}