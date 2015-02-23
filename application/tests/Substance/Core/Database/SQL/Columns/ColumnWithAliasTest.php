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

namespace Substance\Core\Database\SQL\Columns;

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Expressions\AndExpression;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Tests the column alias.
 */
class ColumnWithAliasTest extends AbstractSQLTest {

  /**
   * Test a column alias for a simple column expression.
   */
  public function testBuildOnColumn() {
    $expression = new ColumnWithAlias( new ColumnNameExpression('column'), 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column` AS `col`', $sql );
  }

  /**
   * Test a column alias for an infix expression.
   */
  public function testBuildOnInfixExpression() {
    $infix = new AndExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') );
    $expression = new ColumnWithAlias( $infix, 'col' );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AS `col`', $sql );
  }

  /**
   * Test that constructing a column alias and table alias with the same alias
   * is allowed.
   */
  public function testBuildOneColumnOneTable() {
    $expression = new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' );
    $expression = new TableName( 'table', 'col' );
    // If we get to this point, the test is passed as otherwise an exception
    // would be thrown
  }

  /**
   * Test that constructing multiple column aliases with the same alias is
   * allowed.
   */
  public function testConstructDuplicateColumn() {
    $expression = new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' );
    $expression = new ColumnWithAlias( new ColumnNameExpression('column2'), 'col' );
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
    $expression = new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' );
    $query->addColumn( $expression );
    $expression = new ColumnWithAlias( new ColumnNameExpression('column2'), 'col' );
    $query->addColumn( $expression );
  }

}
