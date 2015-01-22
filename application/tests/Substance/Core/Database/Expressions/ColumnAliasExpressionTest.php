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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Database\Queries\Select;
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
    $query = Select::select('table');
    $expression = new ColumnAliasExpression( $query, new ColumnExpression('column'), 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column` AS `col`', $sql );
  }

  /**
   * Test a column alias for an infix expression.
   */
  public function testBuildOnInfixExpression() {
    $query = Select::select('table');
    $infix = new AndExpression( new ColumnExpression('column1'), new ColumnExpression('column2') );
    $expression = new ColumnAliasExpression( $query, $infix, 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AS `col`', $sql );
  }

  /**
   * Test that multiple column aliases with the same alias are not allowed.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testBuildDuplicateColumn() {
    $query = Select::select('table');
    $expression = new ColumnAliasExpression( $query, new ColumnExpression('column1'), 'col' );
    $expression = new ColumnAliasExpression( $query, new ColumnExpression('column2'), 'col' );
  }

  /**
   * Test a column alias and table alias, both using the same alias name.
   */
  public function testBuildOneColumnOneTable() {
    $query = Select::select('table');
    $expression = new ColumnAliasExpression( $query, new ColumnExpression('column1'), 'col' );
    $expression = new TableAliasExpression( $query, new ColumnExpression('column2'), 'col' );

    // FIXME - Need an assertion here.
  }

}
