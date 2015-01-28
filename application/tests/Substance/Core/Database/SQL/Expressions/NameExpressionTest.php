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

/**
 * Tests the name expression.
 */
class NameExpressionTest extends AbstractDatabaseTest {

  /**
   * Test a name expression.
   */
  public function testBuild() {
    // Test a simple name.
    $expression = new NameExpression('column');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`column`', $sql );

    // Test a name with a period.
    $expression = new NameExpression('column.column');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`column.column`', $sql );

    // Test a name with a hyphen.
    $expression = new NameExpression('column-column');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`column-column`', $sql );

    // Test a name with a quote.
    $expression = new NameExpression('column\'column');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`column\'column`', $sql );

    // Test a name with a backtick.
    $expression = new NameExpression('column`column');
    $sql = $expression->build( $this->connection );
    $this->assertEquals( '`column``column`', $sql );
  }

}
