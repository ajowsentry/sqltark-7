<?php
declare(strict_types=1);

require_once __DIR__. '/../vendor/autoload.php';

use SqlTark\Query;
use SqlTark\Expressions;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;

final class InsertQueryTest extends TestCase
{
    public function testInsertQuery()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);

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
        $valuesString = "(1, 2.2, 'string', '2023-01-02 14:15:16', ?, :var, TRUE, NULL)";

        $query->from('t1')->asInsert($values);

        $output = "INSERT INTO `t1` (`a`, `b`, `c`, `d`, `e`, `f`, `g`, `h`) VALUES {$valuesString}";
        $this->assertEquals($output, $query->compile());
        
        $query->clearComponents();

        $query->from('t1')->asBulkInsert(array_keys($values), array_fill(0, 5, $values));
        
        $output = "INSERT INTO `t1` (`a`, `b`, `c`, `d`, `e`, `f`, `g`, `h`) VALUES " . join(', ', array_fill(0, 5, $valuesString));
        $this->assertEquals($output, $query->compile());

    }
}