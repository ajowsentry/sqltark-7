<?php

declare(strict_types=1);

namespace SqlTark\Component;

use InvalidArgumentException;

final class JoinType
{
    /**
     * Join clause
     */
    public const Join = 0;

    /**
     * Inner Join clause
     */
    public const InnerJoin = 1;
    
    /**
     * Left Join clause
     */
    public const LeftJoin = 2;
    
    /**
     * Right Join clause
     */
    public const RightJoin = 3;
    
    /**
     * Outer Join clause
     */
    public const OuterJoin = 4;
    
    /**
     * Cross Join clause
     */
    public const CrossJoin = 5;
    
    /**
     * Natural Join clause
     */
    public const NaturalJoin = 6;
    
    /**
     * Left Outer Join clause
     */
    public const LeftOuterJoin = 7;
    
    /**
     * Right Outer Join clause
     */
    public const RightOuterJoin = 8;
    
    /**
     * Full Join clause
     */
    public const FullOuterJoin = 9;

    /**
     * @param int $joinType
     * @return string
     */
    public static function nameOf(int $joinType): string
    {
        switch($joinType) {
            case self::Join: return 'Join';
            case self::InnerJoin: return 'InnerJoin';
            case self::LeftJoin: return 'LeftJoin';
            case self::RightJoin: return 'RightJoin';
            case self::OuterJoin: return 'OuterJoin';
            case self::CrossJoin: return 'CrossJoin';
            case self::NaturalJoin: return 'NaturalJoin';
            case self::LeftOuterJoin: return 'LeftOuterJoin';
            case self::RightOuterJoin: return 'RightOuterJoin';
            case self::FullOuterJoin: return 'FullOuterJoin';
        }
        
        throw new InvalidArgumentException("Component type with value {$joinType} not defined");
    }

    /**
     * @param int $joinType
     * @return string
     */
    public static function syntaxOf(int $joinType): string
    {
        switch($joinType) {
            case self::Join: return 'JOIN';
            case self::InnerJoin: return 'INNER JOIN';
            case self::LeftJoin: return 'LEFT JOIN';
            case self::RightJoin: return 'RIGHT JOIN';
            case self::OuterJoin: return 'OUTER JOIN';
            case self::CrossJoin: return 'CROSS JOIN';
            case self::NaturalJoin: return 'NATURAL JOIN';
            case self::LeftOuterJoin: return 'LEFT OUTER JOIN';
            case self::RightOuterJoin: return 'RIGHT OUTER JOIN';
            case self::FullOuterJoin: return 'FULL OUTER JOIN';
        }
        
        throw new InvalidArgumentException("Component type with value {$joinType} not defined");
    }
}
