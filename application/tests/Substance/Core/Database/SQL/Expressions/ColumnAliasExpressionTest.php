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

namespace Substance\Core\Database\SQL\Expressions;

use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;
use Substance\Core\Database\TestDatabase;

/**
 * Tests the column alias expression.
 */
class ColumnAliasExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test a column alias for a simple column expression.
   */
  public function testBuildOnColumn() {
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column'), 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column` AS `col`', $sql );
  }

  /**
   * Test a column alias for an infix expression.
   */
  public function testBuildOnInfixExpression() {
    $infix = new AndExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') );
    $expression = new ColumnAliasExpression( $infix, 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AS `col`', $sql );
  }

  /**
   * Test that constructing a column alias and table alias with the same alias
   * is allowed.
   */
  public function testBuildOneColumnOneTable() {
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' );
    $expression = new TableName( 'table', 'col' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test that constructing multiple column aliases with the same alias is
   * allowed.
   */
  public function testConstructDuplicateColumn() {
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' );
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column2'), 'col' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test that multiple column aliases with the same alias in a single query is
   * not allowed.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testSelectWithDuplicateColumnAlias() {
    $query = Select::select('table');
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' );
    $query->addColumn( $expression );
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column2'), 'col' );
    $query->addColumn( $expression );
  }

}
