<?php
declare(strict_types=1);

namespace Tests\Compiler\SqlServer;

use SqlTark\Query;
use SqlTark\Expressions;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\SqlServerCompiler;
use DateTime;

final class UpdateQueryTest extends TestCase
{
    public function testUpdateQuery()
    {
        $query = new Query;
        $query->setCompiler(new SqlServerCompiler);

        $values = [
            'a' => 1,
            'b' => 2.2,
            'c' => 'string',
            'd' => new DateTime('2023-01-02 14:15:16'),
            'e' => Expressions::variable(),
            'f' => Expressions::variable('var'),
            'g' => true,
            'h' => null,
        ];

        $output = "UPDATE [t1] SET [a] = 1, [b] = 2.2, [c] = 'string', [d] = '2023-01-02 14:15:16', [e] = ?, [f] = :var, [g] = TRUE, [h] = NULL FROM [t1]";
        $query->from('t1')->asUpdate($values);
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output = 'UPDATE TOP (1) ' . substr($output, 7);
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
    
    public function testUpdateQuery_withAlias()
    {
        $query = new Query;
        $query->setCompiler(new SqlServerCompiler);

        $values = [
            'a' => 1,
            'b' => 2.2,
            'c' => 'string',
        ];

        $output = "UPDATE [tt] SET [a] = 1, [b] = 2.2, [c] = 'string' FROM [t1] AS [tt]";
        $query->from('t1 AS tt')->asUpdate($values);
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output = 'UPDATE TOP (1) ' . substr($output, 7);
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
    
    public function testUpdateQuery_withSubQuery()
    {
        $query = new Query;
        $query->setCompiler(new SqlServerCompiler);

        $values = [
            'a' => 1,
            'b' => 2.2,
            'c' => 'string',
        ];

        $output = "UPDATE [_sub_] SET [a] = 1, [b] = 2.2, [c] = 'string' FROM (SELECT * FROM [t1] AS [tt]) AS [_sub_]";

        $from = $query->newQuery()->from('t1 as tt')->alias('_sub_');

        $query->fromQuery($from)->asUpdate($values);
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output = 'UPDATE TOP (1) ' . substr($output, 7);
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
}