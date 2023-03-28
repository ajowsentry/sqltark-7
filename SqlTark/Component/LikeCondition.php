<?php

declare(strict_types=1);

namespace SqlTark\Component;

use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Expressions\AbstractExpression;

class LikeCondition extends AbstractCondition
{
    /**
     * @var AbstractExpression|Query $column
     */
    protected $column;

    /**
     * @var int $type LikeType enum
     */
    protected $type = LikeType::Like;

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var bool $caseSensitive
     */
    protected $caseSensitive = false;

    /**
     * @var ?string $escapeCharacter
     */
    protected $escapeCharacter = null;

    /**
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setCaseSensitive(bool $value): void
    {
        $this->caseSensitive = $value;
    }

    /**
     * @return ?string
     */
    public function getEscapeCharacter(): ?string
    {
        return $this->escapeCharacter;
    }

    /**
     * @param ?string $value
     * @return void
     */
    public function setEscapeCharacter(?string $value): void
    {
        $this->escapeCharacter = $value;
    }

    /**
     * @return AbstractExpression|Query
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param AbstractExpression|Query $value
     * @return void
     */
    public function setColumn($value): void
    {
        $this->column = $value;
    }

    /**
     * @return int LikeType enum
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $value LikeType enum
     * @return void
     */
    public function setType(int $value): void
    {
        $this->type = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->column = Helper::clone($this->column);
    }
}
