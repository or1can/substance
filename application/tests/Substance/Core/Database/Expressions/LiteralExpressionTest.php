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

use Substance\Core\Database\TestConnection;

/**
 * Tests the literal expression.
 */
class LiteralExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestConnection();
  }

  /**
   * Test a boolean false literal expression.
   */
  public function testBuildBooleanFalseNoAlias() {
    $literal = new LiteralExpression( FALSE );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( 'FALSE', $sql );
  }

  /**
   * Test a boolean false literal expression with an alias.
   */
  public function testBuildBooleanFalseWithAlias() {
    $literal = new LiteralExpression( FALSE, 'alias' );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( 'FALSE AS `alias`', $sql );
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
   * Test a boolean true literal expression with an alias.
   */
  public function testBuildBooleanTrueWithAlias() {
    $literal = new LiteralExpression( TRUE, 'alias' );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( 'TRUE AS `alias`', $sql );
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
   * Test a float literal expression with an alias.
   */
  public function testBuildFloatWithAlias() {
    $literal = new LiteralExpression( 5.345, 'alias' );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '5.345 AS `alias`', $sql );
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
   * Test an integer literal expression with an alias.
   */
  public function testBuildIntegerWithAlias() {
    $literal = new LiteralExpression( 5, 'alias' );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '5 AS `alias`', $sql );
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

  /**
   * Test a string literal expression with an alias.
   */
  public function testBuildStringWithAlias() {
    $literal = new LiteralExpression( 'string', 'alias' );
    $sql = $literal->build( $this->connection );

    $this->assertEquals( '\'string\' AS `alias`', $sql );
  }

}
