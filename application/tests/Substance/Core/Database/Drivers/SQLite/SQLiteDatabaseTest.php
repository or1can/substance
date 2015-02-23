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

namespace Substance\Core\Database\Drivers\SQLite;

use Substance\Core\Database\Schema\ColumnImpl;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\Schema\Size;

/**
 * Base for SQLite database tests.
 */
class SQLiteDatabaseTest extends AbstractSQLiteDatabaseTest {

  /**
   * Test building a integer.
   */
  public function testBuildInteger() {
    $integer = new Integer( Size::size( Size::TINY ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
  }

  /**
   * Test creating a table.
   */
  public function testCreateTable() {
    $this->assertCount( 0, $this->database->listTables() );
    $table = $this->database->createTable('table');
    $table->addColumnByName( 'col2', new Integer() );
    $this->database->applyDataDefinitions();
    $this->assertCount( 1, $this->database->listTables() );
    $this->assertTrue( $this->database->hasTableByName('table') );
  }

  /**
   * Test creating a table with no columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testCreateTableNoColumns() {
    $table = $this->database->createTable('table');
    $this->database->applyDataDefinitions();
  }

  public function testListTables() {
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 0, $tables );

    // Create a table and check it is listed now.
    $table = $this->database->createTable('table');
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $table->addColumn( $column );
    $this->database->applyDataDefinitions();
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 1, $tables );
    $this->assertArrayHasKey( 'table', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table'] );

    // Create another table and check it is listed now.
    $table2 = $this->database->createTable('table2');
    $column = new ColumnImpl( $table2, 'col', new Integer() );
    $table2->addColumn( $column );
    $this->database->applyDataDefinitions();
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 2, $tables );
    $this->assertArrayHasKey( 'table', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table'] );
    $this->assertArrayHasKey( 'table2', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table2'] );
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '"', $this->database->quoteChar() );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->database->quoteTable('table');
    $this->assertEquals( '"table"', $quoted );

    // Test a table with database.
    $quoted = $this->database->quoteTable('db.table');
    $this->assertEquals( '"db"."table"', $quoted );
  }

}
