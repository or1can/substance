<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 - 2015 Kevin Rogers
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

namespace Substance\Core\Database\SQL\Queries;

use Substance\Core\Database\SQL\Columns\AllColumns;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Components\OrderBy;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\QueryTest;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Tests select queries.
 */
class SelectTest extends QueryTest {

  /**
   * Tests adding a column by name.
   */
  public function testAddColumnByName() {
    // Try a query with an explicitly stated column.
    $sql = Select::select('table')
      ->addColumnByName('column1')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias.
    $sql = Select::select('table')
    ->addColumnByName( 'column1', 'col1' )
    ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col1` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias and table.
    $sql = Select::select('table')
    ->addColumnByName( 'column1', 'col1', 'table' )
    ->build( $this->connection );
    $this->assertEquals( 'SELECT `table`.`column1` AS `col1` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias and table with
    // database.
    $sql = Select::select('table')
      ->addColumnByName( 'column1', 'col1', 'db.table' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `db`.`table`.`column1` AS `col1` FROM `table`', $sql );
  }

  /**
   * Tests adding an inner join by table reference.
   */
  public function testInnerJoin() {
    // Test an inner join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoin( new TableName('table2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2`', $sql );

    // Test a left join with no aliases and a USING condition.
    // Test an inner join with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoin( new TableName( 'table2', 't2' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2`', $sql );
  }

  /**
   * Tests adding an inner join by table name.
   */
  public function testInnerJoinByName() {
    // Test an inner join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByName('table2')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2`', $sql );

    // Test a left join with no aliases and a USING condition.
    // Test an inner join with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByName( 'table2', 't2' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2`', $sql );
  }

  /**
   * Tests adding an inner join by table name with an on condition.
   */
  public function testInnerJoinByNameOn() {
    // Test an inner join with no aliases and an ON condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByNameOn( 'table2', NULL, new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` ON `column1` = `column2`', $sql );

    // Test an inner join with aliases and an ON condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByNameOn( 'table2', 't2', new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );
  }

  /**
   * Tests adding an inner join by table name with a using condition.
   */
  public function testInnerJoinByNameUsing() {
    // Test an inner join with no aliases and a USING condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByNameUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` USING ( `column1`, `column2` )', $sql );

    // Test an inner join with aliases and a USING condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByNameUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

  /**
   * Tests adding an inner join by table reference with an on condition.
   */
  public function testInnerJoinOn() {
    // Test an inner join with no aliases and an ON condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinOn( new TableName('table2'), new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` ON `column1` = `column2`', $sql );

    // Test an inner join with aliases and an ON condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinOn( new TableName( 'table2', 't2' ), new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );
  }

  /**
   * Tests adding an inner join by table reference with a using condition.
   */
  public function testInnerJoinUsing() {
    // Test an inner join with no aliases and a USING condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinUsing( new TableName('table2'), new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` USING ( `column1`, `column2` )', $sql );

    // Test an inner join with aliases and a USING condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinUsing( new TableName( 'table2', 't2' ), new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

  /**
   * Tests adding an left join by table reference.
   */
  public function testLeftJoin() {
    // Test an inner join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoin( new TableName('table2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2`', $sql );

    // Test a left join with no aliases and a USING condition.
    // Test an inner join with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoin( new TableName( 'table2', 't2' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2`', $sql );
  }

  /**
   * Tests adding an left join by table name.
   */
  public function testLeftJoinByName() {
    // Test an inner join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByName('table2')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2`', $sql );

    // Test a left join with no aliases and a USING condition.
    // Test an inner join with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinByName( 'table2', 't2' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2`', $sql );
  }

  /**
   * Tests adding an left join by table name with an on condition.
   */
  public function testLeftJoinByNameOn() {
    // Test an inner join with no aliases and an ON condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByNameOn( 'table2', NULL, new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` ON `column1` = `column2`', $sql );

    // Test an inner join with aliases and an ON condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinByNameOn( 'table2', 't2', new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );
  }

  /**
   * Tests adding an left join by table name with a using condition.
   */
  public function testLeftJoinByNameUsing() {
    // Test an inner join with no aliases and a USING condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByNameUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` USING ( `column1`, `column2` )', $sql );

    // Test an inner join with aliases and a USING condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinByNameUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

  /**
   * Tests adding an left join by table reference with an on condition.
   */
  public function testLeftJoinOn() {
    // Test an inner join with no aliases and an ON condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinOn( new TableName('table2'), new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` ON `column1` = `column2`', $sql );

    // Test an inner join with aliases and an ON condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinOn( new TableName( 'table2', 't2' ), new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` ON `column1` = `column2`', $sql );
  }

  /**
   * Tests adding an left join by table reference with a using condition.
   */
  public function testLeftJoinUsing() {
    // Test an inner join with no aliases and a USING condition.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinUsing( new TableName('table2'), new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` USING ( `column1`, `column2` )', $sql );

    // Test an inner join with aliases and a USING condition.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinUsing( new TableName( 'table2', 't2' ), new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` USING ( `column1`, `column2` )', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );

    // Try explicitly stating it's not a distinct query.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addColumn( new AllColumns() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table`', $sql );

    // Try a query with an explicitly stated column.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addColumn( new ColumnNameExpression('column1') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addColumn( new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias using add
    // expression method.
    $sql = Select::select('table')
      ->distinct( FALSE )
      ->addExpression( new ColumnNameExpression('column1'), 'col' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table`', $sql );

    // Try a query with an explicitly stated column and alias from a table with
    // an alias.
    $sql = Select::select( 'table', 't' )
      ->distinct( FALSE )
      ->addColumn( new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' ) )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table` AS `t`', $sql );

    // Try a query with an explicitly stated column and alias from a table with
    // an alias using add expression method.
    $sql = Select::select( 'table', 't' )
      ->distinct( FALSE )
      ->addExpression( new ColumnNameExpression('column1'), 'col' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1` AS `col` FROM `table` AS `t`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and no offset from a table with a database specified.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffsetWithDatabase() {
    $sql = Select::select('information_schema.TABLES')
      ->addColumn( new AllColumns() )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, no order and an offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitOneOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->offset( 2 )
      ->build( $this->connection );

    // An offset makes no sense without a limit, the offset should not appear in
    // the SQL.
    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderOneLimitOneOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->limit( 1 )
      ->offset( 2 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingOneOrderOneLimitNoOffset() {
    // Try an order using the default direction.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // Try the same with specifying the ascending sort direction explicitly.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'ASC' )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC LIMIT 1', $sql );

    // And again, with a descending sort direction.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->limit( 1 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * no limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingTwoOrderNoLimitNoOffset() {
    // Try two order expressions using the default direction.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions with both using ascending directions.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'ASC' )
      ->orderBy( new ColumnNameExpression('column2'), 'ASC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions using the different directions.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'ASC' )
      ->orderBy( new ColumnNameExpression('column2'), 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` DESC', $sql );

    // Try two order expressions using the opposite directions.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->orderBy( new ColumnNameExpression('column2'), 'ASC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` ASC', $sql );

    // Try two order expressions with both using the descending directions.
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1'), 'DESC' )
      ->orderBy( new ColumnNameExpression('column2'), 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using the default ascending direction.
    $select = Select::select('table')
      ->addColumn( new AllColumns() );
    $order_by = array(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderByExpressions( $order_by )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified ascending direction.
    $select = Select::select('table')
      ->addColumn( new AllColumns() );
    $order_by = array(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderByExpressions( $order_by, 'ASC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC', $sql );

    // Try two order expressions by adding a prebuilt comma expression of
    // general expressions using a specified descending direction.
    $select = Select::select('table')
      ->addColumn( new AllColumns() );
    $order_by = array(
      new ColumnNameExpression('column1'),
      new ColumnNameExpression('column2')
    );
    $sql = $select->orderByExpressions( $order_by, 'DESC' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` DESC, `column2` DESC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, no group, no having,
   * one limit, two order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereNoGroupNoHavingTwoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->orderBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` ORDER BY `column1` ASC, `column2` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, no having,
   * one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingNoOrderOneLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->limit( 1 );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph LIMIT 1', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, no limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingOneOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph ORDER BY `column1` ASC', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, one
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupOneHavingOneOrderOneLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph ORDER BY `column1` ASC LIMIT 1', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, two
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupTwoHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->having( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph AND `column2` = :dbph_2', $sql );
    $this->assertEquals( array( ':dbph' => 5, ':dbph_2' => 'hello' ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, one group, two
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereOneGroupTwoHavingNoOrderOneLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->having( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->having( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->limit( 1 );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1` HAVING `column1` = :dbph AND `column2` = :dbph_2 LIMIT 1', $sql );
    $this->assertEquals( array( ':dbph' => 5, ':dbph_2' => 'hello' ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, no where, two group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinNoWhereTwoGroupNoHavingNoOrderOneLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new AllColumns() )
      ->groupBy( new ColumnNameExpression('column1') )
      ->groupBy( new ColumnNameExpression('column2') )
      ->limit( 1 )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` GROUP BY `column1`, `column2` LIMIT 1', $sql );
  }

  /**
   * Test a build all, with one column, no join, one where clause, no group, no
   * having, no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, one where clause, no group, no
   * having, no limit, one order and offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->orderBy( new ColumnNameExpression('column1') );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph ORDER BY `column1` ASC', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph GROUP BY `column1`', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, no order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingNoOrderOneLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->limit( 1 );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph GROUP BY `column1` LIMIT 1', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph GROUP BY `column1` ORDER BY `column1` ASC', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, one where, one group, no
   * having, one limit, one order and no offset.
   */
  public function testBuildAllOneColumnNoJoinOneWhereOneGroupNoHavingOneOrderOneLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 1 );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph GROUP BY `column1` ORDER BY `column1` ASC LIMIT 1', $sql );
    $this->assertEquals( array( ':dbph' => 5 ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, no join, three where, no group, no
   * having, no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinThreeWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column3'), new LiteralExpression( 7 ) ) );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph AND `column2` = :dbph_2 AND `column3` = :dbph_3', $sql );
    $this->assertEquals( array( ':dbph' => 5, ':dbph_2' => 'hello', ':dbph_3' => 7 ), $select->getArguments() );
//    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build all, with one column, no join, three where, no group, no
   * having, no limit, one order and offset.
   */
  public function testBuildAllOneColumnNoJoinThreeWhereNoGroupNoHavingOneOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column3'), new LiteralExpression( 7 ) ) )
      ->orderBy( new ColumnNameExpression('column1') );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph AND `column2` = :dbph_2 AND `column3` = :dbph_3 ORDER BY `column1` ASC', $sql );
    $this->assertEquals( array( ':dbph' => 5, ':dbph_2' => 'hello', ':dbph_3' => 7 ), $select->getArguments() );
    //    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5 AND `column2` = \'hello\' AND `column3` = 7', (string) $select );
  }

  /**
   * Test a build all, with one column, no join, two where, no group, no having,
   * no limit, no order and offset.
   */
  public function testBuildAllOneColumnNoJoinTwoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $select = Select::select('table')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('column2'), new LiteralExpression('hello') ) );
    $sql = $select->build( $this->connection );

    // We've used a literal value here, so we must check for placeholders and
    // arguments.
    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = :dbph AND `column2` = :dbph_2', $sql );
    $this->assertEquals( array( ':dbph' => 5, ':dbph_2' => 'hello' ), $select->getArguments() );
  }

  /**
   * Test a build all, with one column, one join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllOneColumnOneJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    // Test an inner join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByName('table2')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2`', $sql );

    // Test a left join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByName('table2')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2`', $sql );
  }

  /**
   * Test a build all, with one column, two joins, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildAllOneColumnTwoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    // Test two inner joins with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByName('table2')
      ->innerJoinByName('table3')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` INNER JOIN `table3`', $sql );

    // Test two left joins with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByName('table2')
      ->leftJoinByName('table3')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` LEFT JOIN `table3`', $sql );

    // Test one inner and one left join with no aliases.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByName('table2')
      ->leftJoinByName('table3')
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` LEFT JOIN `table3`', $sql );

    // Test two inner joins with no aliases and USING conditions.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->innerJoinByNameUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->innerJoinByNameUsing( 'table3', NULL, new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` INNER JOIN `table2` USING ( `column1`, `column2` ) INNER JOIN `table3` USING ( `column3`, `column4` )', $sql );

    // Test two left joins with no aliases and USING conditions.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByNameUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->leftJoinByNameUsing( 'table3', NULL, new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` USING ( `column1`, `column2` ) LEFT JOIN `table3` USING ( `column3`, `column4` )', $sql );

    // Test one inner and one left join with no aliases and USING conditions.
    $sql = Select::select('table1')
      ->addColumn( new AllColumns() )
      ->leftJoinByNameUsing( 'table2', NULL, new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->innerJoinByNameUsing( 'table3', NULL, new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` LEFT JOIN `table2` USING ( `column1`, `column2` ) INNER JOIN `table3` USING ( `column3`, `column4` )', $sql );

    // Test two inner joins with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByName( 'table2', 't2' )
      ->innerJoinByName( 'table3', 't3' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` INNER JOIN `table3` AS `t3`', $sql );

    // Test two inner joins with automatic aliases.
    $select = Select::select( 'table1', 't1' );
    $sql = $select->addColumn( new AllColumns() )
      ->innerJoinByName( 'table2', $table2 = $select->uniqueTableAlias('t') )
      ->innerJoinByName( 'table3', $table3 = $select->uniqueTableAlias('t') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t` INNER JOIN `table3` AS `t2`', $sql );

    // Test two left joins with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinByName( 'table2', 't2' )
      ->leftJoinByName( 'table3', 't3' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` LEFT JOIN `table3` AS `t3`', $sql );

    // Test one inner and one left join with aliases.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->leftJoinByName( 'table2', 't2' )
      ->innerJoinByName( 'table3', 't3' )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` INNER JOIN `table3` AS `t3`', $sql );

    // Test two inner joins with aliases and USING conditions.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByNameUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->innerJoinByNameUsing( 'table3', 't3', new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` ) INNER JOIN `table3` AS `t3` USING ( `column3`, `column4` )', $sql );

    // Test two left joins with aliases and USING conditions.
    $sql = Select::select( 'table1', 't1' )
    ->addColumn( new AllColumns() )
    ->leftJoinByNameUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
    ->leftJoinByNameUsing( 'table3', 't3', new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
    ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` LEFT JOIN `table2` AS `t2` USING ( `column1`, `column2` ) LEFT JOIN `table3` AS `t3` USING ( `column3`, `column4` )', $sql );

    // Test one inner and one left join with aliases and USING conditions.
    $sql = Select::select( 'table1', 't1' )
      ->addColumn( new AllColumns() )
      ->innerJoinByNameUsing( 'table2', 't2', new ColumnNameExpression('column1'), new ColumnNameExpression('column2') )
      ->leftJoinByNameUsing( 'table3', 't3', new ColumnNameExpression('column3'), new ColumnNameExpression('column4') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT * FROM `table1` AS `t1` INNER JOIN `table2` AS `t2` USING ( `column1`, `column2` ) LEFT JOIN `table3` AS `t3` USING ( `column3`, `column4` )', $sql );
  }

  /**
   * Test a build all, with three columns, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildAllThreeColumnsNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumn( new ColumnNameExpression('column2') )
      ->addColumn( new ColumnNameExpression('column3') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2`, `column3` FROM `table`', $sql );
  }

  /**
   * Test a build all, with three columns, two join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildAllThreeColumnsTwoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    // Test two inner joins with automatic aliases and three columns using
    // those aliases.
    $select = Select::select( 'table1', 't1' );
    $sql = $select
      ->innerJoinByName( 'table2', $table2 = $select->uniqueTableAlias('t') )
      ->innerJoinByName( 'table3', $table3 = $select->uniqueTableAlias('t') )
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumnByName( 'column2', NULL, $table2 )
      ->addColumnByName( 'column3', NULL, $table3 )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT `column1`, `t`.`column2`, `t2`.`column3` FROM `table1` AS `t1` INNER JOIN `table2` AS `t` INNER JOIN `table3` AS `t2`', $sql );
  }

  /**
   * Test a build all, with two columns, no join, no where, no group, no having,
   * no limit, no order and no offset.
   */
  public function testBuildAllTwoColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumn( new ColumnNameExpression('column2') )
      ->build( $this->connection );

    $this->assertEquals( 'SELECT `column1`, `column2` FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with one column, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildDistinctOneColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->distinct()
      ->addColumn( new AllColumns() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $sql = Select::select('table')
      ->distinct( TRUE )
      ->addColumn( new AllColumns() )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT * FROM `table`', $sql );
  }

  /**
   * Test a build distinct, with two columns, no join, no where, no group, no
   * having, no limit, no order and no offset.
   */
  public function testBuildDistinctTwoColumnNoJoinNoWhereNoGroupNoHavingNoOrderNoLimitNoOffset() {
    $sql = Select::select('table')
      ->distinct()
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumn( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );

    // Try explicitly stating it's a distinct query.
    $sql = Select::select('table')
      ->distinct( TRUE )
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumn( new ColumnNameExpression('column2') )
      ->build( $this->connection );
    $this->assertEquals( 'SELECT DISTINCT `column1`, `column2` FROM `table`', $sql );
  }

}
