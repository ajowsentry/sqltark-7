<?php
declare(strict_types=1);

require_once __DIR__. '/../vendor/autoload.php';

use SqlTark\Query;
use SqlTark\Expressions;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;

final class UpdateQueryTest extends TestCase
{
    public function testUpdateQuery()
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

        $output = "UPDATE `t1` SET `a` = 1, `b` = 2.2, `c` = 'string', `d` = '2023-01-02 14:15:16', `e` = ?, `f` = :var, `g` = TRUE, `h` = NULL";
        $query->from('t1')->asUpdate($values);
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE 1 = 1";
        $query->equals(1, 1);
        $this->assertEquals($output, $query->compile());

        $output .= " LIMIT 1";
        $query->limit(1);
        $this->assertEquals($output, $query->compile());
    }
}