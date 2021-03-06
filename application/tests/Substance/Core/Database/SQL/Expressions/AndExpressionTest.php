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
 * Tests the and expression.
 */
class AndExpressionTest extends AbstractSQLTest {

  /**
   * Test adding an expression to an and expression.
   */
  public function testAddExpressionToSequence() {
    $left = new ColumnNameExpression('column1');
    $right = new ColumnNameExpression('column2');
    $expr = new AndExpression( $left, $right );
    $expr->addExpressionToSequence( new ColumnNameExpression('column3') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AND `column3`', $sql );
  }

  /**
   * Test adding an expression to an and expression.
   */
  public function testAddExpressionsToSequence() {
    $left = new ColumnNameExpression('column1');
    $right = new ColumnNameExpression('column2');
    // Test adding no expressions.
    $expr = new AndExpression( $left, $right );
    $expr->addExpressionsToSequence();
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2`', $sql );

    // Test adding one expression.
    $expr = new AndExpression( $left, $right );
    $expr->addExpressionsToSequence( new ColumnNameExpression('column3') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AND `column3`', $sql );

    // Test adding multiple expressions.
    $expr = new AndExpression( $left, $right );
    $expr->addExpressionsToSequence( new ColumnNameExpression('column3'), new ColumnNameExpression('column4') );
    $sql = $expr->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2` AND `column3` AND `column4`', $sql );
  }

  /**
   * Test an and expression.
   */
  public function testBuild() {
    $left = new ColumnNameExpression('column1');
    $right = new ColumnNameExpression('column2');
    $equals = new AndExpression( $left, $right );
    $sql = $equals->build( $this->connection );

    $this->assertEquals( '`column1` AND `column2`', $sql );
  }

  /**
   * Test converting to an array.
   */
  public function testToArray() {
    $left = new ColumnNameExpression('column1');
    $right = new ColumnNameExpression('column2');
    $equals = new AndExpression( $left, $right );

    // Test with a simple two expression sequence.
    $this->assertEquals( array( $left, $right ), $equals->toArray() );

    // Add another expression to the sequence.
    $third = new ColumnNameExpression('column3');
    $equals->addExpressionToSequence( $third );
    $this->assertEquals( array( $left, $right, $third ), $equals->toArray() );

    // Add the second expression to the sequence again.
    $third = new ColumnNameExpression('column3');
    $equals->addExpressionToSequence( $right );
    $this->assertEquals( array( $left, $right, $third, $right ), $equals->toArray() );
  }

}
