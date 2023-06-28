<?php

declare(strict_types=1);

namespace SqlTark;

use RuntimeException;
use SqlTark\Query\Traits;
use SqlTark\Query\AbstractQuery;
use SqlTark\Component\ComponentType;
use SqlTark\Query\ConditionInterface;
use SqlTark\Compiler\AbstractCompiler;

/**
 * @property static $where
 * @property static $whereNot
 * @property static $orWhere
 * @property static $orWhereNot
 * @property static $andWhere
 * @property static $andWhereNot
 * @property static $having
 * @property static $havingNot
 * @property static $orHaving
 * @property static $orHavingNot
 * @property static $andHaving
 * @property static $andHavingNot
 */
class Query extends AbstractQuery implements ConditionInterface
{
    use Traits\Aggregate,
        Traits\BaseFrom,
        Traits\Combine,
        Traits\Cte,
        Traits\From,
        Traits\GroupBy,
        Traits\Join,
        Traits\Manipulate,
        Traits\Order,
        Traits\Paging,
        Traits\Select,
        Traits\ExtendedWhere;

    /**
     * @var ?AbstractCompiler $compiler
     */
    protected $compiler = null;

    /**
     * @return static Self object
     */
    public function withWhere()
    {
        $this->conditionComponent = ComponentType::Where;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function withHaving()
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

    /**
     * @return mixed
     */
    public function __get(string $property)
    {
        $name = $property;
        if(substr($name, 0, 2) == 'or') {
            $this->or();
            $name = lcfirst(substr($name, 2));
        }
        elseif(substr($name, 0, 3) == 'and') {
            $this->and();
            $name = lcfirst(substr($name, 3));
        }

        if(substr($name, 0, 5) == 'where') {
            $this->withWhere();
            $name = lcfirst(substr($name, 5));
        }
        elseif(substr($name, 0, 6) == 'having') {
            $this->withHaving();
            $name = lcfirst(substr($name, 6));
        }

        if(substr($name, 0, 3) == 'not') {
            $this->not();
            $name = lcfirst(substr($name, 3));
        }

        if($name == '') {
            return $this;
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $property, E_USER_NOTICE);
        return null;
    }
}