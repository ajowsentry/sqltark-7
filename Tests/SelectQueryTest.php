<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SqlTark\Compiler\MySqlCompiler;
use SqlTark\Component\ComponentType;
use SqlTark\Expressions;
use SqlTark\Query;

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

        $output .= " AND `g` BETWEEN 1 AND 100";
        $query->between('g', 1, 100);
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
        $query->having()->equals(Expressions::literal(1), Expressions::literal(1));
        $this->assertEquals($output, $query->compile());

        $output .= " LIMIT 100, 18446744073709551615";
        $query->offset(100);
        $this->assertEquals($output, $query->compile());

        $output = substr($output, 0, -32);
        $output .= " LIMIT 100, 123";
        $query->limit(123);
        $this->assertEquals($output, $query->compile());

        $query->clearComponents(ComponentType::Limit);
        $query->clearComponents(ComponentType::Offset);

        $output = substr($output, 0, -15);
        $output .= " LIMIT 222";
        $query->limit(222);
        $this->assertEquals($output, $query->compile());
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