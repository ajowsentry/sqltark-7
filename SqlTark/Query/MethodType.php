<?php

declare(strict_types=1);

namespace SqlTark\Query;

use InvalidArgumentException;

final class MethodType
{
    /**
     * Select query method
     */
    public const Select = 1;
    
    /**
     * Aggregate select query method
     */
    public const Aggregate = 2;
    
    /**
     * Join query clause
     */
    public const Join = 3;
    
    /**
     * Insert query method
     */
    public const Insert = 4;
    
    /**
     * Update query method
     */
    public const Update = 5;

    /**
     * Delete query method
     */
    public const Delete = 6;

    /**
     * Auto detect query method
     */
    public const Auto = 0;

    /**
     * @param int $methodType
     * @return string
     */
    public static function nameOf(int $methodType): string
    {
        switch($methodType) {
            case self::Select: return 'Select';
            case self::Aggregate: return 'Aggregate';
            case self::Join: return 'Join';
            case self::Insert: return 'Insert';
            case self::Update: return 'Update';
            case self::Delete: return 'Delete';
            case self::Auto: return 'Auto';
        }
        
        throw new InvalidArgumentException("Component type with value {$methodType} not defined");
    }
}