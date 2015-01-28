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

namespace Substance\Core\Database\SQL\Components;

use Substance\Core\Database\AbstractDatabaseTest;
use Substance\Core\Database\SQL\Components\OrderBy;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Tests the order by component.
 */
class OrderByTest extends AbstractDatabaseTest {

  /**
   * Test an ascending order by expression.
   */
  public function testBuildAscendingOrder() {
    $left = new ColumnNameExpression('column1');
    $expr = new OrderBy( $left, 'ASC' );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` ASC', $sql );
  }

  /**
   * Test a descending order by expression.
   */
  public function testBuildDescendingOrder() {
    $left = new ColumnNameExpression('column1');
    $expr = new OrderBy( $left, 'DESC' );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` DESC', $sql );
  }

  /**
   * Test a neither ascending nor descending direction order by expression.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testInvalidDirection() {
    $left = new ColumnNameExpression('column1');
    $expr = new OrderBy( $left, 'ALLOWED' );
  }

  /**
   * Test that an order by expression cannot be built with an OrderBy
   * as the left hand side.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testNoOrderByOrderBy() {
    $left = new OrderBy( new ColumnNameExpression('column1'), 'DESC' );
    $expr = new OrderBy( $left, 'DESC' );
  }

}
