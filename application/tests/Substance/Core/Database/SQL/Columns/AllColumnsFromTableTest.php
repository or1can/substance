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

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\SQL\Columns\AllColumnsFromTable;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Tests the all columns from table select expression.
 */
class AllColumnsFromTableTest extends AbstractSQLTest {

  /**
   * Test a build of the all columns from table column without a database.
   */
  public function testBuildNoDatabase() {
    $expression = new AllColumnsFromTable('table1');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`table1`.*', $sql );
  }

  /**
   * Test a build of the all columns from table column with a database.
   */
  public function testBuildWithDatabase() {
    $expression = new AllColumnsFromTable('database.table1');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`database`.`table1`.*', $sql );
  }

}
