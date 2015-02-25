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

namespace Substance\Core\Database\Schema;

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\Schema\Types\Integer;

/**
 * Base for database tests.
 */
abstract class AbstractDatabaseTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Connection the database connection for testing
   */
  protected $connection;

  /**
   * @var Database the default database for testing.
   */
  protected $database;

  protected $test_database_names = array( 'test' );

  abstract public function initialise();

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->initialise();
    // Get the default database for the connection.
    $this->database = $this->connection->getDatabase();
  }

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

    // Now try another table with multiple columns.
    $table = $this->database->createTable('table2');
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $table->addColumn( $column );
    $table->addColumnByName( 'col2', new Integer() );
    $table->addColumnByName( 'col3', new Integer( Size::size( Size::TINY ) ) );
    $table->addColumnByName( 'col4', new Integer( Size::size( Size::BIG ) ) );
    $this->database->applyDataDefinitions();
    $this->assertCount( 2, $this->database->listTables() );
    $this->assertTrue( $this->database->hasTableByName('table2') );
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

  /**
   * Test listing tables in a database.
   */
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

}
