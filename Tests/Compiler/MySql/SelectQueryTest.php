<?php
declare(strict_types=1);

namespace Tests\Compiler\MySql;

use SqlTark\Query;
use SqlTark\Expressions;
use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;
use SqlTark\Component\ComponentType;
use DateTime;

final class SelectQueryTest extends TestCase
{
    public function testSelectQuery()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);

        $output = "SELECT * FROM `table` AS `t`";
        $query->from('table as t');
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1` FROM `table` AS `t`";
        $query->select('t.column1');
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1`, 'asdf' FROM `table` AS `t`";
        $query->select(Expressions::literal('asdf'));
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1`, 'asdf', TRUE FROM `table` AS `t`";
        $query->select(Expressions::literal(true));
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1`, 'asdf', TRUE, t.column2 FROM `table` AS `t`";
        $query->select(Expressions::raw('t.column2'));
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1`, 'asdf', TRUE, t.column2, :var FROM `table` AS `t`";
        $query->select(Expressions::variable('var'));
        $this->assertEquals($output, $query->compile());

        $output = "SELECT `t`.`column1`, 'asdf', TRUE, t.column2, :var, ? FROM `table` AS `t`";
        $query->select(Expressions::variable());
        $this->assertEquals($output, $query->compile());

        $output .= " JOIN `table2` AS `tt` ON `t`.`id` = `tt`.`id`";
        $query->join('table2 AS tt', 't.id', '=', 'tt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " LEFT JOIN `table3` AS `ttt` ON `t`.`id` = `ttt`.`id`";
        $query->leftJoin('table3 AS ttt', 't.id', '=', 'ttt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " RIGHT JOIN `table3` AS `ttt` ON `t`.`id` = `ttt`.`id`";
        $query->rightJoin('table3 AS ttt', 't.id', '=', 'ttt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " OUTER JOIN `table2` AS `tt` ON `t`.`id` = `tt`.`id`";
        $query->outerJoin('table2 AS tt', 't.id', '=', 'tt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " LEFT OUTER JOIN `table3` AS `ttt` ON `t`.`id` = `ttt`.`id`";
        $query->leftOuterJoin('table3 AS ttt', 't.id', '=', 'ttt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " RIGHT OUTER JOIN `table3` AS `ttt` ON `t`.`id` = `ttt`.`id`";
        $query->rightOuterJoin('table3 AS ttt', 't.id', '=', 'ttt.id');
        $this->assertEquals($output, $query->compile());

        $output .= " CROSS JOIN `table3` AS `ttt`";
        $query->crossJoin('table3 as ttt');
        $this->assertEquals($output, $query->compile());

        $output .= " NATURAL JOIN `table3` AS `ttt`";
        $query->naturalJoin('table3 as ttt');
        $this->assertEquals($output, $query->compile());

        $output .= " WHERE `a` = 1";
        $query->equals('a', 1);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `b` != 1.23";
        $query->notEquals('b', 1.23);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `c` > TRUE";
        $query->greaterThan('c', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `d` >= 'str'";
        $query->greaterEquals('d', 'str');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `c` < TRUE";
        $query->lesserThan('c', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `d` <= 'str'";
        $query->lesserEquals('d', 'str');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `e` IN (1, 1.23, TRUE, NULL, 'str')";
        $query->in('e', [1, 1.23, true, null, 'str']);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `f` IS NULL";
        $query->isNull('f');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `g` = 'h'";
        $query->where('g', 'h');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` != 'h'";
        $query->where('h', '!=', 'h');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `i` BETWEEN 1 AND 100";
        $query->between('i', 1, 100);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `i` NOT BETWEEN 1 AND 100";
        $query->not->between('i', 1, 100);
        $this->assertEquals($output, $query->compile());

        $output .= " OR `i` > 100";
        $query->or->where('i', '>', 100);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `i` IS NOT NULL";
        $query->not->isNull('i');
        $this->assertEquals($output, $query->compile());

        $output .= " OR NOT (`i` > 100)";
        $query->or->not->where('i', '>', 100);
        $this->assertEquals($output, $query->compile());

        $output .= " AND (`c` = 'd' AND `d` = 'c')";
        $query->group(function($q) { return $q->equals('c', 'd')->equals('d', 'c'); });
        $this->assertEquals($output, $query->compile());

        $output .= " AND EXISTS(SELECT * FROM `tab2`)";
        $query->exists(function($q) { return $q->from('tab2'); });
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zenislev%'";
        $query->like('h', '%zenislev%');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE 'zen%'";
        $query->startsWith('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zen'";
        $query->endsWith('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE '%zen%'";
        $query->contains('h', 'zen');
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE BINARY '%zen%'";
        $query->contains('h', 'zen', true);
        $this->assertEquals($output, $query->compile());

        $output .= " AND `h` LIKE BINARY '%zen!%%' ESCAPE '!'";
        $query->contains('h', 'zen%', true, '!');
        $this->assertEquals($output, $query->compile());

        $output .= " AND MAX(`x`) > '2022-02-22 22:22:22'";
        $query->conditionRaw('MAX(`x`) > ?', new DateTime('2022-02-22 22:22:22'));
        $this->assertEquals($output, $query->compile());

        $output .= " GROUP BY `t`.`id`";
        $query->groupBy('t.id');
        $this->assertEquals($output, $query->compile());

        $output .= " HAVING 1 = 1";
        $query->withHaving()->equals(Expressions::literal(1), Expressions::literal(1));
        $this->assertEquals($output, $query->compile());

        $output .= " LIMIT 100, 18446744073709551615";
        $query->offset(100);
        $this->assertEquals($output, $query->compile());

        $output = substr($output, 0, -32);
        $output .= " LIMIT 100, 123";
        $query->limit('123');
        $this->assertEquals($output, $query->compile());

        $query->limit(null)->offset(null);

        $output = substr($output, 0, -15);
        $output .= " LIMIT 222";
        $query->limit(222);
        $this->assertEquals($output, $query->compile());

        $output = substr($output, 0, -10);
        $output .= " LIMIT :var";
        $query->limit(Expressions::variable('var'));
        $this->assertEquals($output, $query->compile());
    }

    public function testSelectQuery_select()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);
        
        $query->select('column1');
        $expected = 'SELECT `column1`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select column');
        $query->clearComponents();

        $query->select('column1 AS c1');
        $expected = 'SELECT `column1` AS `c1`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select column with alias');
        $query->clearComponents();

        $query->select('column1 AS c1', 'column2');
        $expected = 'SELECT `column1` AS `c1`, `column2`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select multiple column');
        $query->clearComponents();

        $query->select(1, 1.2, true, Expressions::literal('string'));
        $expected = 'SELECT 1, 1.2, TRUE, \'string\'';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select literals');
        $query->clearComponents();

        $query->select(
            Expressions::literal(1)->as('int'),
            Expressions::literal(1.2)->as('float'),
            Expressions::literal(true)->as('bool'),
            Expressions::literal('string')->as('str')
        );
        $expected = 'SELECT 1 AS `int`, 1.2 AS `float`, TRUE AS `bool`, \'string\' AS `str`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select literals with alias');
        $query->clearComponents();

        $query->select(
            Expressions::variable()->as('v1'),
            Expressions::variable('var')->as('v2')
        );
        $expected = 'SELECT ? AS `v1`, :var AS `v2`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select variables with alias');
        $query->clearComponents();

        $query->select(
            Expressions::column('1'),
            Expressions::column('TRUE'),
            Expressions::column('string')
        );
        $expected = 'SELECT `1`, `TRUE`, `string`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select columns using Expressions::column');
        $query->clearComponents();

        $query->select(
            Expressions::column('1 AS int'),
            Expressions::column('true AS bool'),
            Expressions::column('string AS str')
        );
        $expected = 'SELECT `1` AS `int`, `true` AS `bool`, `string` AS `str`';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select columns using Expressions::column and alias');
        $query->clearComponents();

        $query->select(Expressions::raw('1, 1.2, true, null'));
        $expected = 'SELECT 1, 1.2, true, null';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select raw');
        $query->clearComponents();

        $query->select(Expressions::raw('? AS int, ? AS float, ? AS bool, ? AS string', [
            1, 1.2, true, 'string'
        ]));
        $expected = 'SELECT 1 AS int, 1.2 AS float, TRUE AS bool, \'string\' AS string';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select raw with placeholder');
        $query->clearComponents();

        $query->selectRaw('1, 1.2, true, null');
        $expected = 'SELECT 1, 1.2, true, null';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select raw with `selectRaw` method');
        $query->clearComponents();
        
        $query->selectRaw('? AS int, ? AS float, ? AS bool, ? AS string',
            1, 1.2, true, 'string'
        );
        $expected = 'SELECT 1 AS int, 1.2 AS float, TRUE AS bool, \'string\' AS string';
        $actual = $query->compile();
        $this->assertEquals($expected, $actual, 'Test select raw with `selectRaw` method and placeholder');
        $query->clearComponents();
    }

    public function testSelectQuery_withQuery()
    {
        $query = new Query;
        $query->setCompiler(new MySqlCompiler);

        $subQuery = $query->newQuery();
        $subQuery->from('xtable')->select('col1')->limit(1);

        $output = "SELECT * FROM (SELECT `col1` FROM `xtable` LIMIT 1) AS `x`";
        $query->fromQuery($subQuery->alias('x'));
        $this->assertEquals($output, $query->compile());

        $output = "SELECT (SELECT `col1` FROM `xtable` LIMIT 1) AS `xxx` FROM (SELECT `col1` FROM `xtable` LIMIT 1) AS `x`";
        $query->select($subQuery->alias('xxx'));
        $this->assertEquals($output, $query->compile());
    }
}