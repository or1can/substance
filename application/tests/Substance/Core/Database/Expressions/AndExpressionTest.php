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
use Substance\Core\Database\TestConnection;

/**
 * Tests the and expression.
 */
class AndExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestConnection();
  }

  /**
   * Test an and expression.
   */
  public function testBuild() {
    $left = new ColumnExpression('column1');
    $right = new ColumnExpression('column2');
    $equals = new AndExpression( $left, $right );
    $sql = $equals->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2`', $sql );
  }

}
