<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Component\JoinType;

class Join extends AbstractQuery implements JoinConditionInterface
{
    use Traits\From, Traits\JoinCondition;

    /**
     * @var int $type JoinType enum
     */
    protected $type = JoinType::Join;

    /**
     * @return int JoinType enum
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type JoinType enum
     * @return $this Self object
     */
    public function asType(int $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return $this Self object
     */
    public function asInnerJoin()
    {
        return $this->asType(JoinType::InnerJoin);
    }

    /**
     * @return $this Self object
     */
    public function asLeftJoin()
    {
        return $this->asType(JoinType::LeftJoin);
    }

    /**
     * @return $this Self object
     */
    public function asRightJoin()
    {
        return $this->asType(JoinType::RightJoin);
    }

    /**
     * @return $this Self object
     */
    public function asOuterJoin()
    {
        return $this->asType(JoinType::OuterJoin);
    }

    /**
     * @return $this Self object
     */
    public function asNaturalJoin()
    {
        return $this->asType(JoinType::NaturalJoin);
    }

    /**
     * @return $this Self object
     */
    public function asLeftOuterJoin()
    {
        return $this->asType(JoinType::LeftOuterJoin);
    }

    /**
     * @return $this Self object
     */
    public function asRightOuterJoin()
    {
        return $this->asType(JoinType::RightOuterJoin);
    }

    /**
     * @return $this Self object
     */
    public function asFullOuterJoin()
    {
        return $this->asType(JoinType::FullOuterJoin);
    }
}