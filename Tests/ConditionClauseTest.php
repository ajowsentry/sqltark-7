<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SqlTark\Query;

final class ConditionClauseTest extends TestCase
{
    public function testQuery_compare()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->equals('column1', 'string');
        $query->equals('column2', 1);
        $query->equals('column3', 1.01);
        $query->equals('column4', true);
        $query->equals('column5', null);

        $query->orEquals(1, 'string');
        $query->orEquals(2, 1);
        $query->orEquals(3, 1.01);
        $query->orEquals(4, true);
        $query->orEquals(5, null);

        $query->orEquals(1.01, 'string');
        $query->orEquals(2.01, 1);
        $query->orEquals(3.01, 1.01);
        $query->orEquals(4.01, true);
        $query->orEquals(5.01, null);
        
        $query->orEquals(true, 1.01);
        $query->orEquals(false, null);
        $query->orEquals(null, 'null');
    }

    public function testQuery_whereIn()
    {
        $this->expectNotToPerformAssertions();
        
        $query = new Query;

        $q2 = new Query;

        $query->in('col1', [1, 2, 3]);
        $query->in('col2', ['s1', 's2', 's3']);
        $query->in('col3', [1.01, 2.02, 3.03]);
        $query->in('col4', [true, false, null]);
        $query->in('col5', [true, false, null]);
        $query->in('col5', $q2);
    }
}