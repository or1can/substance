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
 * Tests the USING join condition.
 */
class UsingTest extends AbstractSQLTest {

  /**
   * Test an on condition.
   */
  public function testBuild() {
    // Test a using condition with a single column.
    $expr = new Using( new ColumnNameExpression('column1') );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'USING ( `column1` )', $sql );

    // Test a using condition with two columns.
    $expr->addColumnName( new ColumnNameExpression('column2') );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'USING ( `column1`, `column2` )', $sql );

    // Test a using condition with three columns.
    $expr->addColumnName( new ColumnNameExpression('column3') );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'USING ( `column1`, `column2`, `column3` )', $sql );
  }

  /**
   * Test the static builder with no columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testStaticConstructWithNoColumns() {
    // Test a using condition with no columns.
    $expr = Using::using();
  }

  /**
   * Test the static builder with one column.
   */
  public function testStaticConstructWithOneColumn() {
    // Test a using condition with a single column.
    $col1 = new ColumnNameExpression('column1');
    $expr = Using::using( $col1 );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'USING ( `column1` )', $sql );
  }

  /**
   * Test the static builder with illegal columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testStaticConstructWithOneColumnIllegal() {
    // Test a using condition with a single column.
    $col1 = new LiteralExpression('column1');
    $expr = Using::using( $col1 );
  }

  /**
   * Test the static builder with three columns.
   */
  public function testStaticConstructWithThreeColumns() {
    // Test a using condition with a single column.
    $col1 = new ColumnNameExpression('column1');
    $col2 = new ColumnNameExpression('column2');
    $col3 = new ColumnNameExpression('column3');
    $expr = Using::using( $col1, $col2, $col3 );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( 'USING ( `column1`, `column2`, `column3` )', $sql );
  }

  /**
   * Test the static builder with three columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testStaticConstructWithThreeColumnsIllegal() {
    // Test a using condition with a single column.
    $col1 = new ColumnNameExpression('column1');
    $col2 = new LiteralExpression('column2');
    $col3 = new ColumnNameExpression('column3');
    $expr = Using::using( $col1, $col2, $col3 );
  }

}
