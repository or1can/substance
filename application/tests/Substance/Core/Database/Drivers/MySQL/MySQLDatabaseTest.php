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

namespace Substance\Core\Database\Drivers\MySQL;

use Substance\Core\Database\Schema\ColumnImpl;
use Substance\Core\Database\Schema\Types\Integer;
/**
 * Base for MySQL database tests.
 */
class MySQLDatabaseTest extends AbstractMySQLDatabaseTest {

  public function testCreateTable() {
//     $test_db_name = $this->test_database_names[ 0 ];
//     $test = $this->connection->createDatabase( $test_db_name );
//     $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $test );
//     $this->assertEquals( $test_db_name, $test->getName() );
  }

  /**
   * Test creating a table with no columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testCreateTableNoColumns() {
    $table = $this->database->createTable( 'table');
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
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\Schema\MySQLTable', $tables['table'] );

    // Create another table and check it is listed now.
    $table2 = $this->database->createTable('table2');
    $column = new ColumnImpl( $table2, 'col', new Integer() );
    $table2->addColumn( $column );
    $this->database->applyDataDefinitions();
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 2, $tables );
    $this->assertArrayHasKey( 'table', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\Schema\MySQLTable', $tables['table'] );
    $this->assertArrayHasKey( 'table2', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\Schema\MySQLTable', $tables['table2'] );
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '`', $this->database->quoteChar() );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->database->quoteTable('table');
    $this->assertEquals( '`table`', $quoted );

    // Test a table with database.
    $quoted = $this->database->quoteTable('db.table');
    $this->assertEquals( '`db`.`table`', $quoted );
  }

}
