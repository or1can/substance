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

use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;
use Substance\Core\Database\TestDatabase;

/**
 * Tests the column expression.
 */
class ColumnNameExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test a column expression with no table.
   */
  public function testBuildNoTable() {
    $expression = new ColumnNameExpression('column');
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`column`', $sql );
  }

  /**
   * Test a column expression with no alias and with a table.
   */
  public function testBuildNoAliasWithTable() {
    $expression = new ColumnNameExpression( 'column', new TableName('table') );
    $sql = $expression->build( $this->connection );

    $this->assertEquals( '`table`.`column`', $sql );
  }

}
