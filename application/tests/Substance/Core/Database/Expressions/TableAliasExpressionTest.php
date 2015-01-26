<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2015 Kevin Rogers
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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Database\Queries\Select;
use Substance\Core\Database\TestDatabase;

/**
 * Tests the table alias expression.
 */
class TableAliasExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test a table alias for a simple table expression.
   */
  public function testBuildOnTable() {
    $expression = new TableAliasExpression( new ColumnNameExpression('table'), 'tab' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`table` AS `tab`', $sql );

    $sql = Select::select('information_schema.TABLES')
      ->addExpression( new AllColumnsExpression() )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a table alias for an infix expression.
   */
  public function testBuildOnInfixExpression() {
    $infix = new AndExpression( new ColumnNameExpression('table1'), new ColumnNameExpression('table2') );
    $expression = new TableAliasExpression( $infix, 'tab' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`table1` AND `table2` AS `tab`', $sql );
  }

  /**
   * Test that constructing a table alias and column alias with the same alias
   * is allowed.
   */
  public function testBuildOneColumnOneTable() {
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column1'), 'tab' );
    $expression = new TableAliasExpression( new ColumnNameExpression('table1'), 'tab' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test that constructing multiple table aliases with the same alias is
   * allowed.
   */
  public function testConstructDuplicateTable() {
    $expression = new TableAliasExpression( new ColumnNameExpression('table1'), 'tab' );
    $expression = new TableAliasExpression( new ColumnNameExpression('table2'), 'tab' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test that multiple table aliases with the same alias in a single query is
   * not allowed.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testSelectWithDuplicateTableAlias() {
    $query = Select::select('table');
    $expression = new TableAliasExpression( new ColumnNameExpression('table1'), 'tab' );
    // FIXME - This is wrong as adding a table alias to a select list should
    // not be allowed.
    $query->addExpression( $expression );
    $expression = new TableAliasExpression( new ColumnNameExpression('table2'), 'tab' );
    $query->addExpression( $expression );
  }

}
