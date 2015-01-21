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

use Substance\Core\Database\TestDatabase;

/**
 * Tests the is null expression.
 */
class IsNullExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test constructing with an illegal not argument.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testConstructIllegalNotArgument() {
    $left = new ColumnExpression('column1');
    // not must be a boolean
    $expr = new IsNullExpression( $left, 5 );
  }

  /**
   * Test an is null expression.
   */
  public function testBuildIsNull() {
    $left = new ColumnExpression('column1');
    // Test building with the default $not.
    $expr = new IsNullExpression( $left );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`column1` IS NULL', $sql );

    // Test building with the specified $not.
    $expr = new IsNullExpression( $left, FALSE );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`column1` IS NULL', $sql );

    // Test building with the shorthand function.
    $expr = IsNullExpression::isNull( $left );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`column1` IS NULL', $sql );
  }

  /**
   * Test an is not null expression.
   */
  public function testBuildIsNotNull() {
    $left = new ColumnExpression('column1');
    // Test building with the constructor.
    $expr = new IsNullExpression( $left, TRUE );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`column1` IS NOT NULL', $sql );

    // Test building with the shorthand function.
    $expr = IsNullExpression::isNotNull( $left );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`column1` IS NOT NULL', $sql );
  }

}
