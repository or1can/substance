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

namespace Substance\Core\Database\SQL;

use Substance\Core\Database\AbstractDatabaseTest;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Tests for the abstract query class.
 */
abstract class QueryTest extends AbstractDatabaseTest {

  /**
   * Test that defining multiple column aliases with the same alias in a single
   * query is not allowed.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testDefineDuplicateColumnAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' ) );
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column2'), 'col' ) );
  }

  /**
   * Test that defining multiple table aliases with the same alias in a single
   * query is not allowed.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testDefineDuplicateTableAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $query->defineTableName( new TableName( 'table1', 't' ) );
    $query->defineTableName( new TableName( 'table2', 't' ) );
  }

  /**
   * Test defining one column aliases.
   */
  public function testDefineOneColumnAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $this->assertFalse( $query->hasColumnAlias( 'col' ) );
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column1'), 'col' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col' ) );
  }

  /**
   * Test defining one table aliases.
   */
  public function testDefineOneTableAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $this->assertFalse( $query->hasTableAlias('t') );
    $query->defineTableName( new TableName( 'table1', 't' ) );
    $this->assertTrue( $query->hasTableAlias('t') );
  }

  /**
   * Test defining two column aliases.
   */
  public function testDefineTwoColumnAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $this->assertFalse( $query->hasColumnAlias( 'col1' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col2' ) );
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column1'), 'col1' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col1' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col2' ) );
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column2'), 'col2' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col1' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col2' ) );
  }

  /**
   * Test defining two table aliases.
   */
  public function testDefineTwoTableAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    $this->assertFalse( $query->hasTableAlias( 't1' ) );
    $this->assertFalse( $query->hasTableAlias( 't2' ) );
    $query->defineTableName( new TableName( 'table1', 't1' ) );
    $this->assertTrue( $query->hasTableAlias( 't1' ) );
    $this->assertFalse( $query->hasTableAlias( 't2' ) );
    $query->defineTableName( new TableName( 'table2', 't2' ) );
    $this->assertTrue( $query->hasTableAlias( 't1' ) );
    $this->assertTrue( $query->hasTableAlias( 't2' ) );
  }

  /**
   * Test generating one unique column aliases.
   */
  public function testUniqueOneColumnAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    // Check aliases col, col1 and col2 do not exist.
    $this->assertFalse( $query->hasColumnAlias( 'col' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col1' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col2' ) );
    // Define col
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column'), 'col' ) );
    // Check alias col exists and col1 and col2 do not exist.
    $this->assertTrue( $query->hasColumnAlias( 'col' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col1' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col2' ) );
    // Reserve col
    $reserved = $query->uniqueColumnAlias('col');
    $this->assertNotNull( $reserved );
    $this->assertNotEquals( 'col', $reserved );
    // Check alias col and col2 exist and col1 does not exist.
    $this->assertTrue( $query->hasColumnAlias( 'col' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col1' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col2' ) );
    // Check that alias col2 was reserved.
    $this->assertFalse( $query->hasColumnAlias( 'col2', TRUE ) );
    // Now define the reserved alias
    $query->defineColumnAlias( new ColumnWithAlias( new ColumnNameExpression('column'), $reserved ) );
    // Check alias col and col2 exist and col1 does not exist.
    $this->assertTrue( $query->hasColumnAlias( 'col' ) );
    $this->assertFalse( $query->hasColumnAlias( 'col1' ) );
    $this->assertTrue( $query->hasColumnAlias( 'col2' ) );
  }

  /**
   * Test generating one unique table aliases.
   */
  public function testUniqueOneTableAlias() {
    // TODO - This should really use the child classes query type.
    $query = Select::select('table');
    // Check aliases col, col1 and col2 do not exist.
    $this->assertFalse( $query->hasTableAlias( 't' ) );
    $this->assertFalse( $query->hasTableAlias( 't1' ) );
    $this->assertFalse( $query->hasTableAlias( 't2' ) );
    // Define t
    $query->defineTableName( new TableName( 'table', 't' ) );
    // Check alias t exists and t1 and t2 do not exist.
    $this->assertTrue( $query->hasTableAlias( 't' ) );
    $this->assertFalse( $query->hasTableAlias( 't1' ) );
    $this->assertFalse( $query->hasTableAlias( 't2' ) );
    // Reserve t
    $reserved = $query->uniqueTableAlias('t');
    $this->assertNotNull( $reserved );
    $this->assertNotEquals( 't', $reserved );
    // Check alias t and t2 exist and t1 does not exist.
    $this->assertTrue( $query->hasTableAlias( 't' ) );
    $this->assertFalse( $query->hasTableAlias( 't1' ) );
    $this->assertTrue( $query->hasTableAlias( 't2' ) );
    // Check that alias t2 was reserved.
    $this->assertFalse( $query->hasTableAlias( 't2', TRUE ) );
    // Now define the reserved alias
    $query->defineTableName( new TableName( 'table', $reserved ) );
    // Check alias t and t2 exist and t1 does not exist.
    $this->assertTrue( $query->hasTableAlias( 't' ) );
    $this->assertFalse( $query->hasTableAlias( 't1' ) );
    $this->assertTrue( $query->hasTableAlias( 't2' ) );
  }

}
