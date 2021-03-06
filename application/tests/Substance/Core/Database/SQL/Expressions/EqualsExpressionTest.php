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

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Tests the equals expression.
 */
class EqualsExpressionTest extends AbstractSQLTest {

  /**
   * Test an equals expression with no alias.
   */
  public function testBuildNoAlias() {
    $left = new ColumnNameExpression('column1');
    $right = new ColumnNameExpression('column2');
    $equals = new EqualsExpression( $left, $right );
    $sql = $equals->build( $this->connection );

    $this->assertEquals( '`column1` = `column2`', $sql );
  }

}
