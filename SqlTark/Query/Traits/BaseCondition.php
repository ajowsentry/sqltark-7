<?php

declare(strict_types=1);

namespace SqlTark\Query\Traits;

use SqlTark\Utilities\Helper;
use SqlTark\Component\LikeType;
use SqlTark\Component\InCondition;
use SqlTark\Component\RawCondition;
use SqlTark\Component\CompareClause;
use SqlTark\Component\LikeCondition;
use SqlTark\Component\NullCondition;
use SqlTark\Component\GroupCondition;
use SqlTark\Component\ExistsCondition;
use SqlTark\Component\BetweenCondition;

trait BaseCondition
{
    /**
     * @var bool $orFlag
     */
    protected $orFlag = false;

    /**
     * @var bool $notFlag
     */
    protected $notFlag = false;

    /**
     * @return static Self object
     */
    public function and()
    {
        $this->orFlag = false;
        return $this;
    }

    /**
     * @return static Self object
     */
    public function or()
    {
        $this->orFlag = true;
        return $this;
    }

    /**
     * @param bool $value
     * @return static Self object
     */
    public function not(bool $value = true)
    {
        $this->notFlag = $value;
        return $this;
    }

    /**
     * @return bool
     */
    protected function getOr(): bool
    {
        $return = $this->orFlag;
        $this->orFlag = false;

        return $return;
    }

    /**
     * @return bool
     */
    protected function getNot(): bool
    {
        $return = $this->notFlag;
        $this->notFlag = false;

        return $return;
    }

    public function compare($left, string $operator, $right)
    {
        $component = new CompareClause;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setLeft(Helper::resolveExpression($left, true));
        $component->setOperator($operator);
        $component->setRight(Helper::resolveExpression($right));

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function in($column, $list)
    {
        $resolvedValues = is_array($list)
            ? Helper::resolveExpressionList($list)
            : $list;

        $component = new InCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setValues($resolvedValues);

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function isNull($column)
    {
        $component = new NullCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function between($column, $low, $high)
    {
        $component = new BetweenCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setLower(Helper::resolveExpression($low));
        $component->setHigher(Helper::resolveExpression($high));

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function group($condition)
    {
        $component = new GroupCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setCondition(Helper::resolveCondition($condition, $this));

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function exists($query)
    {
        $component = new ExistsCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setQuery(Helper::resolveQuery($query, $this));

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function like($column, string $value, bool $caseSensitive = false, ?string $escapeCharacter = null, int $likeType = LikeType::Like)
    {
        $component = new LikeCondition;
        $component->setNot($this->getNot());
        $component->setOr($this->getOr());
        $component->setColumn(Helper::resolveExpression($column, true));
        $component->setValue($value);
        $component->setCaseSensitive($caseSensitive);
        $component->setEscapeCharacter($escapeCharacter);
        $component->setType($likeType);

        return $this->addComponent($this->conditionComponent, $component);
    }

    public function conditionRaw(string $expression, ...$bindings)
    {
        $component = new RawCondition;
        $component->setExpression($expression);
        $component->setBindings(Helper::resolveExpressionList($bindings));

        return $this->addComponent($this->conditionComponent, $component);
    }
}