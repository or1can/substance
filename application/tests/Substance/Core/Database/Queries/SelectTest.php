<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
 *
 * Substance is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Substance is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Substance.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Substance\Core\Database\Queries;

use Substance\Core\Database\Expressions\AllColumnsExpression;
use Substance\Core\Database\Expressions\ColumnExpression;
use Substance\Core\Database\Expressions\EqualsExpression;
use Substance\Core\Database\Expressions\LiteralExpression;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Database\TestDatabase;

/**
 * Tests select queries.
 */
class SelectTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test a build with one column, no where, no group, no having, no limit and
   * no offset.
   */
  public function testBuildOneColumnNoWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build with one column, no where, no group, no having, no limit and
   * no offset from a table with a database specified.
   */
  public function testBuildOneColumnNoWhereNoGroupNoHavingNoLimitNoOffsetWithDatabase() {
    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build with one column, no where, no group, no having, no limit and
   * an offset.
   */
  public function testBuildOneColumnNoWhereNoGroupNoHavingNoLimitOneOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    // Add an offset. As this makes no sense without a limit, the offset should
    // not appear in the SQL.
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build with one column, no where, no group, no having, a limit and
   * no offset.
   */
  public function testBuildOneColumnNoWhereNoGroupNoHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, no where, no group, no having, a limit and
   * offset.
   */
  public function testBuildOneColumnNoWhereNoGroupNoHavingOneLimitOneOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build with one column, no where, one group, no having, no limit and
   * no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`', $sql );
  }

  /**
   * Test a build with one column, no where, one group, one having, no limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupOneHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
//    $select->having( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );
//    $select->having( new EqualsExpression( new ColumnExpression('column3'), new LiteralExpression( 7 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5', $sql );
  }

  /**
   * Test a build with one column, no where, one group, two having, no limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupTwoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->having( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );
//    $select->having( new EqualsExpression( new ColumnExpression('column3'), new LiteralExpression( 7 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build with one column, no where, one group, no having, one limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupNoHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, no where, one group, one having, one limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupOneHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, no where, one group, two having, one limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereOneGroupTwoHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->having( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 AND `column2` = \'hello\' LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, no where, two group, no having, one limit
   * and no offset.
   */
  public function testBuildOneColumnNoWhereTwoGroupNoHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->groupBy( new ColumnExpression('column2') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`, `column2` LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, one where clause, no group, no having, no
   * limit and offset.
   */
  public function testBuildOneColumnOneWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5', $sql );
  }

  /**
   * Test a build with one column, one where, one group, no having, one limit
   * and no offset.
   */
  public function testBuildOneColumnOneWhereOneGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1`', $sql );
  }

  /**
   * Test a build with one column, one where, one group, no having, one limit
   * and no offset.
   */
  public function testBuildOneColumnOneWhereOneGroupNoHavingOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build with one column, three where, no group, no having, no limit
   * and offset.
   */
  public function testBuildOneColumnThreeWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->where( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );
    $select->where( new EqualsExpression( new ColumnExpression('column3'), new LiteralExpression( 7 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', $sql );
//    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build with one column, two where, no group, no having, no limit and
   * offset.
   */
  public function testBuildOneColumnTwoWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->where( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build with three columns, no where, no group, no having, no limit
   * and no offset.
   */
  public function testBuildThreeColumnsNoWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );
    $select->addExpression( new ColumnExpression('column3') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2`, `column3` FROM `table`', $sql );
  }

  /**
   * Test a build with two columns, no where, no group, no having, no limit and
   * no offset.
   */
  public function testBuildTwoColumnNoWhereNoGroupNoHavingNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2` FROM `table`', $sql );
  }

}
