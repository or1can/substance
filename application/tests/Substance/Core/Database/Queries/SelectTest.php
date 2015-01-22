<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 - 2015 Kevin Rogers
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
use Substance\Core\Database\Expressions\OrderByExpression;
use Substance\Core\Database\Expressions\CommaExpression;

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
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );

    // Try explicitly stating it's not a distinct query.
    $select = new Select('table');
    $select->distinct( FALSE );
    $select->addExpression( new AllColumnsExpression() );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and no offset from a table with a database specified.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffsetWithDatabase() {
    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and an offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitOneOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    // Add an offset. As this makes no sense without a limit, the offset should
    // not appear in the SQL.
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderOneLimitOneOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingOneOrderOneLimitNoOffset() {
    // Try an order using the default direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1') );
    $select->limit( 1 );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // Try the same with specifying the ascending sort direction explicitly.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1'), 'ASC' );
    $select->limit( 1 );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // And again, with a descending sort direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1'), 'DESC' );
    $select->limit( 1 );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC LIMIT 1', $sql );

    // Try an order using an explicit order by expression with ascending
    // direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'ASC' ) );
    $select->limit( 1 );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // Try an order using an explicit order by expression with descending
    // direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'DESC' ) );
    $select->limit( 1 );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * two order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingTwoOrderNoLimitNoOffset() {
    // Try two order expressions using the default direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column2') );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions using the different directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1'), 'ASC' );
    $select->orderBy( new ColumnExpression('column2'), 'DESC' );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions using the opposite directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1'), 'DESC' );
    $select->orderBy( new ColumnExpression('column2'), 'ASC' );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions with both using the descending directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1'), 'DESC' );
    $select->orderBy( new ColumnExpression('column2'), 'DESC' );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions using explicit order by expressions with
    // ascending direction
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'ASC' ) );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column2'), 'ASC' ) );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions using explicit order by expressions with
    // different directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'ASC' ) );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column2'), 'DESC' ) );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions using explicit order by expressions with
    // opposite directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'DESC' ) );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column2'), 'ASC' ) );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions with both using the descending directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column1'), 'DESC' ) );
    $select->orderBy( new OrderByExpression( new ColumnExpression('column2'), 'DESC' ) );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with ascending directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnExpression('column1'), 'ASC' ),
      new OrderByExpression( new ColumnExpression('column2'), 'ASC' )
    );
    $select->orderBy( $order_by );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with different directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnExpression('column1'), 'ASC' ),
      new OrderByExpression( new ColumnExpression('column2'), 'DESC' )
    );
    $select->orderBy( $order_by );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with opposite directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnExpression('column1'), 'DESC' ),
      new OrderByExpression( new ColumnExpression('column2'), 'ASC' )
    );
    $select->orderBy( $order_by );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with descending directions.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnExpression('column1'), 'DESC' ),
      new OrderByExpression( new ColumnExpression('column2'), 'DESC' )
    );
    $select->orderBy( $order_by );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using the default ascending direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
        new ColumnExpression('column1'),
        new ColumnExpression('column2')
    );
    $select->orderBy( $order_by );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified ascending direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
        new ColumnExpression('column1'),
        new ColumnExpression('column2')
    );
    $select->orderBy( $order_by, 'ASC' );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified descending direction.
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
        new ColumnExpression('column1'),
        new ColumnExpression('column2')
    );
    $select->orderBy( $order_by, 'DESC' );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingTwoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->orderBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column2') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, no
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingNoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, no
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingOneOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->having( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->orderBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, two having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupTwoHavingNoOrderNoLimitNoOffset() {
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
   * Test a build all, with one column, no where, one group, two having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupTwoHavingNoOrderOneLimitNoOffset() {
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
   * Test a build all, with one column, no where, two group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereTwoGroupNoHavingNoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->groupBy( new ColumnExpression('column1') );
    $select->groupBy( new ColumnExpression('column2') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`, `column2` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, one where clause, no group, no having,
   * no limit, no order and offset.
   */
  public function testBuildAllOneColumnOneWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, one where clause, no group, no having,
   * no limit, one order and offset.
   */
  public function testBuildAllOneColumnOneWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->groupBy( new ColumnExpression('column1') );
    $select->orderBy( new ColumnExpression('column1') );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, three where, no group, no having, no
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnThreeWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
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
   * Test a build all, with one column, three where, no group, no having, no
   * limit, one order and offset.
   */
  public function testBuildAllOneColumnThreeWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->where( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );
    $select->where( new EqualsExpression( new ColumnExpression('column3'), new LiteralExpression( 7 ) ) );
    $select->orderBy( new ColumnExpression('column1') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7 ORDER BY `column1` ASC', $sql );
//    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build all, with one column, two where, no group, no having, no
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnTwoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );
    $select->where( new EqualsExpression( new ColumnExpression('column2'), new LiteralExpression('hello') ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build all, with three columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllThreeColumnsNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );
    $select->addExpression( new ColumnExpression('column3') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2`, `column3` FROM `table`', $sql );
  }

  /**
   * Test a build all, with two columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllTwoColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2` FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with one column, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildDistinctOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->distinct();
    $select->addExpression( new AllColumnsExpression() );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $select = new Select('table');
    $select->distinct( TRUE );
    $select->addExpression( new AllColumnsExpression() );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with two columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildDistinctTwoColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = new Select('table');
    $select->distinct();
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $select = new Select('table');
    $select->distinct( TRUE );
    $select->addExpression( new ColumnExpression('column1') );
    $select->addExpression( new ColumnExpression('column2') );
    $sql = $select->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );
  }

}
