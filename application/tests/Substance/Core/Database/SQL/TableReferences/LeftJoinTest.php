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

namespace Substance\Core\Database\SQL\TableReferences;

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\On;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\Using;

/**
 * Tests the left join table reference.
 */
class LeftJoinTest extends AbstractSQLTest {

  /**
   * Test a left join on two tables with no condition.
   */
  public function testBuildJoinNoCondition() {
    // Test a join with two simple table names with no aliases.
    $expr = new LeftJoin( new TableName('table1'), new TableName('table2') );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` LEFT JOIN `table2`', $sql );

    // Test a join with two simple table names with aliases.
    $expr = new LeftJoin( new TableName( 'table1', 't1' ), new TableName( 'table2', 't2' ) );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` AS `t1` LEFT JOIN `table2` AS `t2`', $sql );

    // Test a join with one simple table names with no alias a left join.
    $expr = new LeftJoin( new TableName( 'table1', 't1' ), new TableName( 'table2', 't2' ) );
    $expr = new LeftJoin( $expr, new TableName( 'table3', 't3' ) );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` AS `t1` LEFT JOIN `table2` AS `t2` LEFT JOIN `table3` AS `t3`', $sql );
  }

  /**
   * Test a left join on two tables with no condition.
   */
  public function testBuildJoinOn() {
    // Test a join with two simple table names with no aliases.
    $expr = new LeftJoin(
      new TableName('table1'),
      new TableName('table2'),
      new On( new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
    );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` LEFT JOIN `table2` ON `column1` = `column2`', $sql );

    // Test a join with two simple table names with no aliases.
    $expr = new LeftJoin(
      new TableName( 'table1', 't1' ),
      new TableName( 'table2', 't2' ),
      new On( new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
    );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` AS `t1` LEFT JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );
  }

  /**
   * Test a left join on two tables with no condition.
   */
  public function testBuildJoinUsing() {
    // Test a join with a USING condition with a single column.
    $expr = new LeftJoin(
      new TableName('table1'),
      new TableName('table2'),
      new Using( new ColumnNameExpression('column1') )
    );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` LEFT JOIN `table2` USING ( `column1` )', $sql );

    // Test a join with a USING condition with two single column.
    $expr = new LeftJoin(
      new TableName( 'table1', 't1' ),
      new TableName( 'table2', 't2' ),
      Using::using( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
    );
    $sql = $expr->build( $this->connection );
    $this->assertEquals( '`table1` AS `t1` LEFT JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

}
