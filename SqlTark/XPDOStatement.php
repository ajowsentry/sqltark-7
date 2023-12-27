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
     * @param null|(callable(array<string,scalar|null>):T) $objectCreator
     * @return null|T
     */
    public function fetchClass(string $className, $objectCreator = null): ?object
    {
        $row = parent::fetch(PDO::FETCH_ASSOC);
        if(false === $row || is_null($row))
            return null;
        
        if(is_null($objectCreator)) {
            return new $className($row);
        }

        return $objectCreator($row);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @param null|(callable(array<string,scalar|null>):T) $objectCreator
     * @return list<T>
     */
    public function fetchClassAll(string $className, $objectCreator = null): array
    {
        return iterator_to_array($this->fetchClassIterate($className, $objectCreator), false);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     * @param null|(callable(array<string,scalar|null>):T) $objectCreator
     * @return Generator<int,T,null,void>
     */
    public function fetchClassIterate(string $className, $objectCreator = null): Generator
    {
        if(is_null($objectCreator)) {
            while(false !== ($row = parent::fetch(PDO::FETCH_ASSOC))) {
                yield new $className($row);
            }
        }
        else {
            while(false !== ($row = parent::fetch(PDO::FETCH_ASSOC))) {
                yield $objectCreator($row);
            }
        }
    }

    /**
     * Same as XPDOStatement::fetch, except it returns null on error instead of false
     * @param int|null $mode
     * @param int|null $cursorOrientation
     * @param int|null $cursorOffset
     * @return mixed
     */
    public function fetchOne($mode = PDO::FETCH_BOTH, $cursorOrientation = PDO::FETCH_ORI_NEXT, $cursorOffset = 0)
    {
        return parent::fetch($mode, $cursorOrientation, $cursorOffset) ?: null;
    }
}