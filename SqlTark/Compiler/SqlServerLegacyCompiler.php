<?php

declare(strict_types=1);

namespace SqlTark\Compiler;

class SqlServerLegacyCompiler extends SqlServerCompiler
{
    use SqlServerLegacy\SelectQueryCompiler;
}