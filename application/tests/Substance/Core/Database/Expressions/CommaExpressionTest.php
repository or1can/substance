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
 * Tests the and expression.
 */
class CommaExpressionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestDatabase();
  }

  /**
   * Test adding an expression to a comma expression.
   */
  public function testAddExpressionToSequence() {
    $left = new ColumnExpression('column1');
    $right = new ColumnExpression('column2');
    $expr = new CommaExpression( $left, $right );
    $expr->addExpressionToSequence( new ColumnExpression('column3') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1`, `column2`, `column3`', $sql );
  }

  /**
   * Test adding expressions to a comma expression.
   */
  public function testAddExpressionsToSequence() {
    $left = new ColumnExpression('column1');
    $right = new ColumnExpression('column2');
    // Test adding no expressions.
    $expr = new CommaExpression( $left, $right );
    $expr->addExpressionsToSequence();
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1`, `column2`', $sql );

    // Test adding one expression.
    $expr = new CommaExpression( $left, $right );
    $expr->addExpressionsToSequence( new ColumnExpression('column3') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1`, `column2`, `column3`', $sql );

    // Test adding multiple expressions.
    $expr = new CommaExpression( $left, $right );
    $expr->addExpressionsToSequence( new ColumnExpression('column3'), new ColumnExpression('column4') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1`, `column2`, `column3`, `column4`', $sql );
  }

  /**
   * Test an and expression.
   */
  public function testBuild() {
    $left = new ColumnExpression('column1');
    $right = new ColumnExpression('column2');
    $equals = new CommaExpression( $left, $right );
    $sql = $equals->build( $this->connection );

    $this->assertEquals( '`column1`, `column2`', $sql );
  }

  /**
   * Test converting to an array.
   */
  public function testToArray() {
    $left = new ColumnExpression('column1');
    $right = new ColumnExpression('column2');
    $equals = new CommaExpression( $left, $right );

    // Test with a simple two expression sequence.
    $this->assertEquals( array( $left, $right ), $equals->toArray() );

    // Add another expression to the sequence.
    $third = new ColumnExpression('column3');
    $equals->addExpressionToSequence( $third );
    $this->assertEquals( array( $left, $right, $third ), $equals->toArray() );

    // Add the second expression to the sequence again.
    $third = new ColumnExpression('column3');
    $equals->addExpressionToSequence( $right );
    $this->assertEquals( array( $left, $right, $third, $right ), $equals->toArray() );
  }

}
