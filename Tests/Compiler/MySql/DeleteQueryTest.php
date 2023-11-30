<?php
declare(strict_types=1);

namespace Tests\Compiler\MySql;

use SqlTark\Query;
use SqlTark\Expressions;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;


final class DeleteQueryTest extends TestCase
{
    public function testDeleteQuery()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);

        $output = "DELETE FROM `t1`";
        $query->from('t1')->asDelete();
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output .= " LIMIT 1";
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
}