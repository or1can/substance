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
use Substance\Core\Database\Expressions\ColumnAliasExpression;
use Substance\Core\Database\Expressions\ColumnNameExpression;
use Substance\Core\Database\Expressions\CommaExpression;
use Substance\Core\Database\Expressions\EqualsExpression;
use Substance\Core\Database\Expressions\LiteralExpression;
use Substance\Core\Database\Expressions\OrderByExpression;
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
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );

    // Try explicitly stating it's not a distinct query.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );

    // Try a query with an explicitly stated column.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addExpression( new ColumnNameExpression('column1') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias.
    $select = Select::select('table');
    $sql = $select->distinct( FALSE )
      ->addExpression( new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and no offset from a table with a database specified.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffsetWithDatabase() {
    $sql = Select::select('information_schema.TABLES')
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * no order and an offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitOneOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->offset( 2 )
      ->build( $this->connection );

    // An offset makes no sense without a limit, the offset should not appear in
    // the SQL.
    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingNoOrderOneLimitOneOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->limit( 1 )
      ->offset( 2 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingOneOrderOneLimitNoOffset() {
    // Try an order using the default direction.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // Try the same with specifying the ascending sort direction explicitly.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1'), 'ASC' )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // And again, with a descending sort direction.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC LIMIT 1', $sql );

    // Try an order using an explicit order by expression with ascending
    // direction.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'ASC' ) )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // Try an order using an explicit order by expression with descending
    // direction.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'DESC' ) )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, no limit,
   * two order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingTwoOrderNoLimitNoOffset() {
    // Try two order expressions using the default direction.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions using the different directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1'), 'ASC' )
      ->orderBy( new ColumnNameExpression('column2'), 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions using the opposite directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->orderBy( new ColumnNameExpression('column2'), 'ASC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions with both using the descending directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->orderBy( new ColumnNameExpression('column2'), 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions using explicit order by expressions with
    // ascending direction
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'ASC' ) )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column2'), 'ASC' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions using explicit order by expressions with
    // different directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'ASC' ) )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column2'), 'DESC' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions using explicit order by expressions with
    // opposite directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'DESC' ) )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column2'), 'ASC' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions with both using the descending directions.
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column1'), 'DESC' ) )
      ->orderBy( new OrderByExpression( new ColumnNameExpression('column2'), 'DESC' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with ascending directions.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnNameExpression('column1'), 'ASC' ),
      new OrderByExpression( new ColumnNameExpression('column2'), 'ASC' )
    );
    $sql = $select->orderBy( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with different directions.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnNameExpression('column1'), 'ASC' ),
      new OrderByExpression( new ColumnNameExpression('column2'), 'DESC' )
    );
    $sql = $select->orderBy( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with opposite directions.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnNameExpression('column1'), 'DESC' ),
      new OrderByExpression( new ColumnNameExpression('column2'), 'ASC' )
    );
    $sql = $select->orderBy( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // explicit order by expressions with descending directions.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new OrderByExpression( new ColumnNameExpression('column1'), 'DESC' ),
      new OrderByExpression( new ColumnNameExpression('column2'), 'DESC' )
    );
    $sql = $select->orderBy( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using the default ascending direction.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderBy( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified ascending direction.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderBy( $order_by, 'ASC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified descending direction.
    $select = Select::select('table')
      ->addExpression( new AllColumnsExpression() );
    $order_by = new CommaExpression(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderBy( $order_by, 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );
  }

  /**
   * Test a build all, with one column, no where, no group, no having, one
   * limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoWhereNoGroupNoHavingTwoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, no
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, no
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, one having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupOneHavingOneOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, two having, no
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupTwoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->having( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build all, with one column, no where, one group, two having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereOneGroupTwoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->having( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 AND `column2` = \'hello\' LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no where, two group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoWhereTwoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->groupBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`, `column2` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, one where clause, no group, no having,
   * no limit, no order and offset.
   */
  public function testBuildAllOneColumnOneWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, one where clause, no group, no having,
   * no limit, one order and offset.
   */
  public function testBuildAllOneColumnOneWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, one where, one group, no having, one
   * limit, one order and no offset.
   */
  public function testBuildAllOneColumnOneWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, three where, no group, no having, no
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnThreeWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column3'), new LiteralExpression( 7 ) ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', $sql );
//    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build all, with one column, three where, no group, no having, no
   * limit, one order and offset.
   */
  public function testBuildAllOneColumnThreeWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column3'), new LiteralExpression( 7 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7 ORDER BY `column1` ASC', $sql );
//    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build all, with one column, two where, no group, no having, no
   * limit, no order and offset.
   */
  public function testBuildAllOneColumnTwoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build all, with three columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllThreeColumnsNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->addExpression( new ColumnNameExpression('column3') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2`, `column3` FROM `table`', $sql );
  }

  /**
   * Test a build all, with two columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildAllTwoColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2` FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with one column, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildDistinctOneColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->distinct()
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $sql = Select::select('table')
      ->distinct( TRUE )
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with two columns, no where, no group, no having, no
   * limit, no order and no offset.
   */
  public function testBuildDistinctTwoColumnNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->distinct()
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $sql = Select::select('table')
      ->distinct( TRUE )
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );
  }

}
