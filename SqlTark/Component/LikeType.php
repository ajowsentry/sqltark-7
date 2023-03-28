<?php

declare(strict_types=1);

namespace SqlTark\Component;

use InvalidArgumentException;

final class LikeType
{
    /**
     * Like clause
     */
    public const Like = 0;

    /**
     * Starts clause
     */
    public const Starts = 1;

    /**
     * Ends clause
     */
    public const Ends = 2;

    /**
     * Contains clause
     */
    public const Contains = 3;

    /**
     * @param int $likeType
     * @return string
     */
    public static function nameOf(int $likeType): string
    {
        switch($likeType) {
            case self::Like: return 'Like';
            case self::Starts: return 'Starts';
            case self::Ends: return 'Ends';
            case self::Contains: return 'Contains';
        }
        
        throw new InvalidArgumentException("Component type with value {$likeType} not defined");
    }
}
