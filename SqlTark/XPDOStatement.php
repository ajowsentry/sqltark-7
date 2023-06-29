<?php

declare(strict_types=1);

namespace SqlTark;

use PDO;
use Generator;
use PDOStatement;

final class XPDOStatement extends PDOStatement
{
    /**
     * @template T of object
     * @param class-string<T> $className
     * @return null|T
     */
    public function fetchClass(string $className): ?object
    {
        $row = parent::fetch(PDO::FETCH_ASSOC);
        return false === $row ? null : new $className($row);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @return list<T>
     */
    public function fetchClassAll(string $className): array
    {
        return iterator_to_array($this->fetchClassIterate($className), false);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @return Generator<int,T,null,void>
     */
    public function fetchClassIterate(string $className): Generator
    {
        while(false !== ($row = parent::fetch(PDO::FETCH_ASSOC))) {
            yield new $className($row);
        }
    }

    /**
     * Same as XPDOStatement::fetch, except it returns null on error instead of false
     * @return mixed
     */
    public function fetchOne($mode = PDO::FETCH_BOTH, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        return parent::fetch($mode, $cursorOrientation, $cursorOffset) ?: null;
    }
}