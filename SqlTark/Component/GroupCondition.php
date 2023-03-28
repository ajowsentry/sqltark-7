<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Utilities\Helper;
use SqlTark\Query\ConditionInterface;

class GroupCondition extends AbstractCondition
{
    /**
     * @var ConditionInterface $condition
     */
    protected $condition;

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }

    /**
     * @param ConditionInterface $value
     * @return void
     */
    public function setCondition(ConditionInterface $value): void
    {
        $this->condition = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->condition = Helper::clone($this->condition);
    }
}
