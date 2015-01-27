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

namespace Substance\Core\Database\SQL\Queries;

use Substance\Core\Database\SQL\Expressions\AllColumnsExpression;
use Substance\Core\Database\SQL\Expressions\ColumnAliasExpression;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\CommaExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Expressions\OrderByExpression;
use Substance\Core\Database\SQL\Queries\Select;
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
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
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
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addExpression( new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias from a table with
    // an alias.
    $sql = Select::select( 'table', 't' )
      ->distinct( FALSE )
      ->addExpression( new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table` AS `t`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and no offset from a table with a database specified.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffsetWithDatabase() {
    $sql = Select::select('information_schema.TABLES')
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and an offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitOneOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->offset( 2 )
      ->build( $this->connection );

    // An offset makes no sense without a limit, the offset should not appear in
    // the SQL.
    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderOneLimitOneOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->limit( 1 )
      ->offset( 2 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingOneOrderOneLimitNoOffset() {
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
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingTwoOrderNoLimitNoOffset() {
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
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingTwoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingOneOrderOneLimitNoOffset() {
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
   * Test a build all, with one column, no join, no where, one group, two
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupTwoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->having( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, two
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupTwoHavingNoOrderOneLimitNoOffset() {
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
   * Test a build all, with one column, no join, no where, two group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereTwoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->groupBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`, `column2` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where clause, no group, no
   * having, no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where clause, no group, no
   * having, no limit, one order and offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
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
   * Test a build all, with one column, no join, three where, no group, no
   * having, no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinThreeWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
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
   * Test a build all, with one column, no join, three where, no group, no
   * having, no limit, one order and offset.
   */
  public function testBuildAllOneColumnNoJoinThreeWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
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
   * Test a build all, with one column, no join, two where, no group, no having,
   * no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinTwoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new AllColumnsExpression() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\'', $sql );
  }

  /**
   * Test a build all, with one column, one join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    // Test a join with no aliases.
    $sql = Select::select('table1')
      ->addExpression( new AllColumnsExpression() )
      ->innerJoin('table2')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2`', $sql );

    // Test a join with no aliases and an ON condition.
    $sql = Select::select('table1')
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinOn( 'table2', NULL, new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` ON `column1` = `column2`', $sql );

    // Test a join with no aliases and a USING condition.
    $sql = Select::select('table1')
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` USING ( `column1`, `column2` )', $sql );

    // Test a join with aliases.
    $sql = Select::select( 'table1', 't1' )
    ->addExpression( new AllColumnsExpression() )
    ->innerJoin( 'table2', 't2' )
    ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2`', $sql );

    // Test a join with aliases and an ON condition.
    $sql = Select::select( 'table1', 't1' )
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinOn( 'table2', 't2', new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );

    // Test a join with aliases and a USING condition.
    $sql = Select::select( 'table1', 't1' )
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

  /**
   * Test a build all, with one column, two joins, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnTwoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    // Test two joins with no aliases.
    $sql = Select::select('table1')
      ->addExpression( new AllColumnsExpression() )
      ->innerJoin('table2')
      ->innerJoin('table3')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` INNER JOIN `table3`', $sql );

    // Test two joins with no aliases and USING conditions.
    $sql = Select::select('table1')
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->innerJoinUsing( 'table3', NULL, new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` USING ( `column1`, `column2` ) INNER JOIN `table3` USING ( `column3`, `column4` )', $sql );

    // Test two joins with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addExpression( new AllColumnsExpression() )
      ->innerJoin( 'table2', 't2' )
      ->innerJoin( 'table3', 't3' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` INNER JOIN `table3` AS `t3`', $sql );

    // Test two joins with aliases and USING conditions.
    $sql = Select::select( 'table1', 't1' )
      ->addExpression( new AllColumnsExpression() )
      ->innerJoinUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->innerJoinUsing( 'table3', 't3', new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` ) INNER JOIN `table3` AS `t3` USING ( `column3`, `column4` )', $sql );

  }

  /**
   * Test a build all, with three columns, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildAllThreeColumnsNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->addExpression( new ColumnNameExpression('column3') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2`, `column3` FROM `table`', $sql );
  }

  /**
   * Test a build all, with two columns, no join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllTwoColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addExpression( new ColumnNameExpression('column1') )
      ->addExpression( new ColumnNameExpression('column2') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2` FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with one column, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildDistinctOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
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
   * Test a build distinct, with two columns, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildDistinctTwoColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
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
