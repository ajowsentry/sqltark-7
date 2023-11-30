<?php

declare(strict_types=1);

namespace SqlTark\Compiler;

use DateTimeInterface;
use SqlTark\Utilities\Helper;

class SqlServerCompiler extends AbstractCompiler
{
    use Traits\ExpressionCompiler,
        SqlServer\SelectQueryCompiler,
        Traits\InsertQueryCompiler,
        SqlServer\UpdateQueryCompiler,
        SqlServer\DeleteQueryCompiler;

    /**
     * {@inheritdoc}
     */
    protected $openingIdentifier = '[';

    /**
     * {@inheritdoc}
     */
    protected $closingIdentifier = ']';

    /**
     * {@inheritdoc}
     */
    public function quote($value, bool $quoteLike = false): string
    {
        if (is_string($value)) {
            return "'" . str_replace("'", "''", $value) . "'";
        } elseif (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } elseif (is_scalar($value)) {
            return (string) $value;
        } elseif (is_null($value)) {
            return 'NULL';
        } elseif ($value instanceof DateTimeInterface) {
            return "'" . $value->format('Y-m-d H:i:s') . "'";
        }

        Helper::throwInvalidArgumentException("Could not resolve value from '%s' type.", $value);
    }
}