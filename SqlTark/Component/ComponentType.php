<?php

declare(strict_types=1);

namespace SqlTark\Component;

use InvalidArgumentException;

final class ComponentType
{
    /**
     * Select clause
     */
    public const Select = 1;

    /**
     * Aggregate clause
     */
    public const Aggregate = 2;

    /**
     * From clause
     */
    public const From = 3;

    /**
     * Join clause
     */
    public const Join = 4;

    /**
     * Where clause
     */
    public const Where = 5;

    /**
     * Group by clause
     */
    public const GroupBy = 6;

    /**
     * Having clause
     */
    public const Having = 7;

    /**
     * Window clause
     */
    public const Window = 8;

    /**
     * Partition clause
     */
    public const Partition = 9;

    /**
     * Frame clause
     */
    public const Frame = 10;

    /**
     * Order by clause
     */
    public const OrderBy = 11;

    /**
     * Limit clause
     */
    public const Limit = 12;

    /**
     * Offset clause
     */
    public const Offset = 13;

    /**
     * Combine clause
     */
    public const Combine = 14;

    /**
     * Common table expression clause
     */
    public const CTE = 15;

    /**
     * Insert clause
     */
    public const Insert = 16;

    /**
     * Update clause
     */
    public const Update = 17;

    /**
     * @param int $componentType
     * @return string
     */
    public static function nameOf(int $componentType): string
    {
        switch($componentType) {
            case self::Select: return 'Select';
            case self::Aggregate: return 'Aggregate';
            case self::From: return 'From';
            case self::Join: return 'Join';
            case self::Where: return 'Where';
            case self::GroupBy: return 'GroupBy';
            case self::Having: return 'Having';
            case self::Window: return 'Window';
            case self::Partition: return 'Partition';
            case self::Frame: return 'Frame';
            case self::OrderBy: return 'OrderBy';
            case self::Limit: return 'Limit';
            case self::Offset: return 'Offset';
            case self::Combine: return 'Combine';
            case self::CTE: return 'CTE';
            case self::Insert: return 'Insert';
            case self::Update: return 'Update';
        }

        throw new InvalidArgumentException("Component type with value {$componentType} not defined");
    }
}