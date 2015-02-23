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

/**
 * Tests the literal expression.
 */
class LiteralExpressionTest extends AbstractSQLTest {

  /**
   * Test a boolean false literal expression.
   */
  public function testBuildBooleanFalseNoAlias() {
    $literal = new LiteralExpression( FALSE );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( 'FALSE', $sql );
  }

  /**
   * Test a boolean true literal expression.
   */
  public function testBuildBooleanTrueNoAlias() {
    $literal = new LiteralExpression( TRUE );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( 'TRUE', $sql );
  }

  /**
   * Test a float literal expression.
   */
  public function testBuildFloatNoAlias() {
    $literal = new LiteralExpression( 5.345 );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '5.345', $sql );
  }

  /**
   * Test an integer literal expression.
   */
  public function testBuildIntegerNoAlias() {
    $literal = new LiteralExpression( 5 );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '5', $sql );
  }

  /**
   * Test an object literal expression.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testBuildObjectNoAlias() {
    $literal = new LiteralExpression( new \stdClass() );
  }

  /**
   * Test a string literal expression.
   */
  public function testBuildStringNoAlias() {
    $literal = new LiteralExpression('string');
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '\'string\'', $sql );
  }

}
