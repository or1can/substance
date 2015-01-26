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
 * Tests the table name expression.
 */
class TableNameExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test a table expression with no database and no alias.
   */
  public function testBuildNoDatabaseNoAlias() {
    $expression = new TableNameExpression('table');
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`table`', $sql );
  }

  /**
   * Test a table expression with no database and an alias.
   */
  public function testBuildNoDatabaseWithAlias() {
    $expression = new TableNameExpression( 'table', 'alias' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`table` AS `alias`', $sql );
  }

  /**
   * Test that constructing a column alias and table alias with the same alias
   * is allowed.
   */
  public function testBuildOneColumnOneTable() {
    $expression = new ColumnAliasExpression( new ColumnNameExpression('column1'), 'col' );
    $expression = new TableNameExpression( 'table', 'col' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test a table expression with a database and no alias.
   */
  public function testBuildWithDatabseNoAlias() {
    $expression = new TableNameExpression('schema.table');
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`schema`.`table`', $sql );
  }

  /**
   * Test a table expression with a database and an alias.
   */
  public function testBuildWithDatabseWithAlias() {
    $expression = new TableNameExpression( 'schema.table', 'alias' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`schema`.`table` AS `alias`', $sql );
  }

  /**
   * Test that constructing multiple table aliases with the same alias is
   * allowed.
   */
  public function testConstructDuplicateTable() {
    $expression = new TableNameExpression( 'table1', 'alias' );
    $expression = new TableNameExpression( 'table2', 'alias' );
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
    $expression = new TableNameExpression( 'table1', 'tab' );
    // FIXME - This is wrong as adding a table alias to a select list should
    // not be allowed.
    $query->addExpression( $expression );
    $expression = new TableNameExpression( 'table2', 'tab' );
    $query->addExpression( $expression );
  }

}
