<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SqlTark\Query;
use SqlTark\Expressions as Exp;

final class ConditionClauseTest extends TestCase
{
    public function testQuery_compare()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->compare('column1', '=', 'string');
        $query->compare('column2', '=', 1);
        $query->compare('column3', '=', 1.01);
        $query->compare('column4', '=', true);
        $query->compare('column5', '=', null);
        $query->compare('column6', '=', Exp::variable());
        $query->compare('column7', '=', Exp::variable('var'));

        $query->orCompare(1, '=', 'string');
        $query->orCompare(2, '=', 1);
        $query->orCompare(3, '=', 1.01);
        $query->orCompare(4, '=', true);
        $query->orCompare(5, '=', null);
        $query->orCompare(6, '=', Exp::variable());
        $query->orCompare(7, '=', Exp::variable('var'));

        $query->orCompare(1.01, '=', 'string');
        $query->orCompare(2.01, '=', 1);
        $query->orCompare(3.01, '=', 1.01);
        $query->orCompare(4.01, '=', true);
        $query->orCompare(5.01, '=', null);
        $query->orCompare(6.01, '=', Exp::variable());
        $query->orCompare(7.01, '=', Exp::variable('var'));
        
        $query->orCompare(true, '=', 1.01);
        $query->orCompare(false, '=', null);
        $query->orCompare(null, '=', 'null');
    }

    public function testQuery_equals()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->equals('column1', 'string');
        $query->equals('column2', 1);
        $query->equals('column3', 1.01);
        $query->equals('column4', true);
        $query->equals('column5', null);
        $query->equals('column6', Exp::variable());
        $query->equals('column7', Exp::variable('var'));

        $query->orEquals(1, 'string');
        $query->orEquals(2, 1);
        $query->orEquals(3, 1.01);
        $query->orEquals(4, true);
        $query->orEquals(5, null);
        $query->orEquals(6, Exp::variable());
        $query->orEquals(7, Exp::variable('var'));

        $query->orEquals(1.01, 'string');
        $query->orEquals(2.01, 1);
        $query->orEquals(3.01, 1.01);
        $query->orEquals(4.01, true);
        $query->orEquals(5.01, null);
        $query->orEquals(6.01, Exp::variable());
        $query->orEquals(7.01, Exp::variable('var'));
        
        $query->orEquals(true, 1.01);
        $query->orEquals(false, null);
        $query->orEquals(null, 'null');
    }
    
    public function testQuery_greaterThan()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->greaterThan('column1', 'string');
        $query->greaterThan('column2', 1);
        $query->greaterThan('column3', 1.01);
        $query->greaterThan('column4', true);
        $query->greaterThan('column5', null);
        $query->greaterThan('column6', Exp::variable());
        $query->greaterThan('column7', Exp::variable('var'));

        $query->orGreaterThan(1, 'string');
        $query->orGreaterThan(2, 1);
        $query->orGreaterThan(3, 1.01);
        $query->orGreaterThan(4, true);
        $query->orGreaterThan(5, null);
        $query->orGreaterThan(6, Exp::variable());
        $query->orGreaterThan(7, Exp::variable('var'));

        $query->orGreaterThan(1.01, 'string');
        $query->orGreaterThan(2.01, 1);
        $query->orGreaterThan(3.01, 1.01);
        $query->orGreaterThan(4.01, true);
        $query->orGreaterThan(5.01, null);
        $query->orGreaterThan(6.01, Exp::variable());
        $query->orGreaterThan(7.01, Exp::variable('var'));
        
        $query->orGreaterThan(true, 1.01);
        $query->orGreaterThan(false, null);
        $query->orGreaterThan(null, 'null');
    }
    
    public function testQuery_greaterEquals()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->greaterEquals('column1', 'string');
        $query->greaterEquals('column2', 1);
        $query->greaterEquals('column3', 1.01);
        $query->greaterEquals('column4', true);
        $query->greaterEquals('column5', null);
        $query->greaterEquals('column6', Exp::variable());
        $query->greaterEquals('column7', Exp::variable('var'));

        $query->orGreaterEquals(1, 'string');
        $query->orGreaterEquals(2, 1);
        $query->orGreaterEquals(3, 1.01);
        $query->orGreaterEquals(4, true);
        $query->orGreaterEquals(5, null);
        $query->orGreaterEquals(6, Exp::variable());
        $query->orGreaterEquals(7, Exp::variable('var'));

        $query->orGreaterEquals(1.01, 'string');
        $query->orGreaterEquals(2.01, 1);
        $query->orGreaterEquals(3.01, 1.01);
        $query->orGreaterEquals(4.01, true);
        $query->orGreaterEquals(5.01, null);
        $query->orGreaterEquals(6.01, Exp::variable());
        $query->orGreaterEquals(7.01, Exp::variable('var'));
        
        $query->orGreaterEquals(true, 1.01);
        $query->orGreaterEquals(false, null);
        $query->orGreaterEquals(null, 'null');
    }
    
    public function testQuery_lesserEquals()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->lesserEquals('column1', 'string');
        $query->lesserEquals('column2', 1);
        $query->lesserEquals('column3', 1.01);
        $query->lesserEquals('column4', true);
        $query->lesserEquals('column5', null);
        $query->lesserEquals('column6', Exp::variable());
        $query->lesserEquals('column7', Exp::variable('var'));

        $query->orLesserEquals(1, 'string');
        $query->orLesserEquals(2, 1);
        $query->orLesserEquals(3, 1.01);
        $query->orLesserEquals(4, true);
        $query->orLesserEquals(5, null);
        $query->orLesserEquals(6, Exp::variable());
        $query->orLesserEquals(7, Exp::variable('var'));

        $query->orLesserEquals(1.01, 'string');
        $query->orLesserEquals(2.01, 1);
        $query->orLesserEquals(3.01, 1.01);
        $query->orLesserEquals(4.01, true);
        $query->orLesserEquals(5.01, null);
        $query->orLesserEquals(6.01, Exp::variable());
        $query->orLesserEquals(7.01, Exp::variable('var'));
        
        $query->orLesserEquals(true, 1.01);
        $query->orLesserEquals(false, null);
        $query->orLesserEquals(null, 'null');
    }
    
    public function testQuery_lesserThan()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->lesserThan('column1', 'string');
        $query->lesserThan('column2', 1);
        $query->lesserThan('column3', 1.01);
        $query->lesserThan('column4', true);
        $query->lesserThan('column5', null);
        $query->lesserThan('column6', Exp::variable());
        $query->lesserThan('column7', Exp::variable('var'));

        $query->orLesserThan(1, 'string');
        $query->orLesserThan(2, 1);
        $query->orLesserThan(3, 1.01);
        $query->orLesserThan(4, true);
        $query->orLesserThan(5, null);
        $query->orLesserThan(6, Exp::variable());
        $query->orLesserThan(7, Exp::variable('var'));

        $query->orLesserThan(1.01, 'string');
        $query->orLesserThan(2.01, 1);
        $query->orLesserThan(3.01, 1.01);
        $query->orLesserThan(4.01, true);
        $query->orLesserThan(5.01, null);
        $query->orLesserThan(6.01, Exp::variable());
        $query->orLesserThan(7.01, Exp::variable('var'));
        
        $query->orLesserThan(true, 1.01);
        $query->orLesserThan(false, null);
        $query->orLesserThan(null, 'null');
    }
    
    public function testQuery_where()
    {
        $this->expectNotToPerformAssertions();

        $query = new Query;

        $query->where('column1', 'string');
        $query->where('column2', 1);
        $query->where('column3', 1.01);
        $query->where('column4', true);
        $query->where('column5', null);
        $query->where('column6', Exp::variable());
        $query->where('column7', Exp::variable('var'));

        $query->orWhere(1, 'string');
        $query->orWhere(2, 1);
        $query->orWhere(3, 1.01);
        $query->orWhere(4, true);
        $query->orWhere(5, null);
        $query->orWhere(6, Exp::variable());
        $query->orWhere(7, Exp::variable('var'));

        $query->orWhere(1.01, 'string');
        $query->orWhere(2.01, 1);
        $query->orWhere(3.01, 1.01);
        $query->orWhere(4.01, true);
        $query->orWhere(5.01, null);
        $query->orWhere(6.01, Exp::variable());
        $query->orWhere(7.01, Exp::variable('var'));
        
        $query->orWhere(true, 1.01);
        $query->orWhere(false, null);
        $query->orWhere(null, 'null');
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
        $query->in('col7', [Exp::variable(), Exp::variable('var')]);
    }
}