<?php

declare(strict_types=1);

namespace SqlTark\Component;

use InvalidArgumentException;

final class CombineType
{
    /**
     * Union Clause
     */
    public const Union = 1;

    /**
     * Except Clause
     */
    public const Except = 2;

    /**
     * Intersect Clause
     */
    public const Intersect = 3;

    /**
     * @param int $combineType
     * @return string
     */
    public static function nameOf(int $combineType): string
    {
        switch($combineType) {
            case self::Union: return 'Union';
            case self::Except: return 'Except';
            case self::Intersect: return 'Intersect';
        }
        
        throw new InvalidArgumentException("Combine type with value {$combineType} not defined");
    }

    /**
     * @param int $combineType
     * @return string
     */
    public static function syntaxOf(int $combineType): string
    {
        switch($combineType) {
            case self::Union: return 'UNION';
            case self::Except: return 'EXCEPT';
            case self::Intersect: return 'INTERSECT';
        }
        
        throw new InvalidArgumentException("Combine type with value {$combineType} not defined");
    }
}
