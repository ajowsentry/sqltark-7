<?php
declare(strict_types=1);

namespace Tests\Compiler\SqlServer;

use SqlTark\Query;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\SqlServerCompiler;

final class DeleteQueryTest extends TestCase
{
    public function testDeleteQuery()
    {
        $query = new Query;
        $query->setCompiler(new SqlServerCompiler);

        $output = "DELETE FROM [t1]";
        $query->from('t1')->asDelete();
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output = "DELETE TOP (1) FROM [t1] WHERE 1 = 1";
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
}