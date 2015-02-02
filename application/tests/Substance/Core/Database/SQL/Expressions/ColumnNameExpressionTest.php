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

namespace Substance\Core\Database\SQL\Expressions;

use Substance\Core\Database\AbstractDatabaseTest;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Tests the column expression.
 */
class ColumnNameExpressionTest extends AbstractDatabaseTest {

  /**
   * Test a column expression with no table.
   */
  public function testBuildNoTable() {
    $expression = new ColumnNameExpression('column');
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column`', $sql );
  }

  /**
   * Test a column expression with no alias and a table with no alias.
   */
  public function testBuildNoAliasWithTableNoAlias() {
    // Test without a database specified.
    $expression = new ColumnNameExpression( 'column', new TableName( 'table' ) );
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`table`.`column`', $sql );

    // Test with a database specified.
    $expression = new ColumnNameExpression( 'column', new TableName( 'db.table' ) );
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`db`.`table`.`column`', $sql );
  }

  /**
   * Test a column expression with no alias and a table with an alias.
   */
  public function testBuildNoAliasWithTableWithAlias() {
    // Test without a database specified.
    $expression = new ColumnNameExpression( 'column', new TableName( 'table', 't' ) );
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`t`.`column`', $sql );
  }

}
