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

namespace Substance\Core\Database\SQL\TableReferences\JoinConditions;

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;

/**
 * Tests the ON join condition.
 */
class OnTest extends AbstractSQLTest {

  /**
   * Test an on condition.
   */
  public function testBuild() {
    // Test an on condition with a simple literal expression.
    $expr = new On( new LiteralExpression( 1 ) );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'ON 1', $sql );

    // Test an on condition with a equality expression.
    $expr = new On( new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'ON `column1` = `column2`', $sql );
  }

}
