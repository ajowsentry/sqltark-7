<?php

declare(strict_types=1);

namespace SqlTark;

use RuntimeException;
use SqlTark\Query\Traits;
use SqlTark\Query\AbstractQuery;
use SqlTark\Component\ComponentType;
use SqlTark\Query\ConditionInterface;
use SqlTark\Compiler\AbstractCompiler;

class Query extends AbstractQuery implements ConditionInterface
{
    use Traits\Aggregate,
        Traits\BaseFrom,
        Traits\Combine,
        Traits\Condition,
        Traits\Cte,
        Traits\From,
        Traits\GroupBy,
        Traits\Join,
        Traits\Manipulate,
        Traits\Order,
        Traits\Paging,
        Traits\Select;

    /**
     * @var ?AbstractCompiler $compiler
     */
    protected $compiler = null;

    /**
     * @return static Self object
     */
    public function where()
    {
        $this->conditionComponent = ComponentType::Where;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function having()
    {
        $this->conditionComponent = ComponentType::Having;
        return $this;
    }

    /**
     * @return ?AbstractCompiler
     */
    public function getCompiler(): ?AbstractCompiler
    {
        return $this->compiler;
    }

    /**
     * @param AbstractCompiler $compiler
     * @return void
     */
    public function setCompiler(AbstractCompiler $compiler): void
    {
        $this->compiler = $compiler;
    }

    /**
     * @return string
     */
    public function compile(?Query $query = null): string
    {
        if(is_null($this->compiler)) {
            throw new RuntimeException("Compiler is not set.");
        }

        return $this->compiler->compileQuery($query ?? $this);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->compile();
    }
}