<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class CompareClause extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $left
     */
    protected $left;

    /**
     * @var string $operator
     */
    protected $operator;

    /**
     * @var AbstractExpression|Query $right
     */
    protected $right;

    /**
     * @return AbstractExpression|Query
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setLeft($value): void
    {
        $this->left = $value;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setOperator(string $value): void
    {
        $this->operator = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setRight($value): void
    {
        $this->right = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->left = Helper::clone($this->left);
        $this->right = Helper::clone($this->right);
    }
}
