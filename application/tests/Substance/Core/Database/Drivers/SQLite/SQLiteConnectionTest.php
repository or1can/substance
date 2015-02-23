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

/**
 * Base for SQLite connection tests.
 */
class SQLiteConnectionTest extends AbstractSQLiteConnectionTest {

  public function testCreateDatabase() {
    $test_db_name = $this->test_database_names[ 0 ];
    $test = $this->connection->createDatabase( $test_db_name );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\SQLite\SQLiteDatabase', $test );
    $this->assertEquals( $test_db_name, $test->getName() );
  }

  public function testListDatabases() {
    $databases = $this->connection->listDatabases();
    $this->assertTrue( is_array( $databases ) );
    $this->assertCount( 1, $databases );
    $this->assertArrayHasKey( 'main', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\SQLite\SQLiteDatabase', $databases['main'] );

    // Create a new database and check it is listed now.
    $test_db_name = $this->test_database_names[ 0 ];
    $this->connection->createDatabase( $test_db_name );
    $databases = $this->connection->listDatabases();
    $this->assertTrue( is_array( $databases ) );
    $this->assertCount( 2, $databases );
    $this->assertArrayHasKey( 'main', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\SQLite\SQLiteDatabase', $databases['main'] );
    $this->assertArrayHasKey( 'test', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\SQLite\SQLiteDatabase', $databases['test'] );
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '"', $this->connection->quoteChar() );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->connection->quoteTable('table');
    $this->assertEquals( '"table"', $quoted );

    // Test a table with database.
    $quoted = $this->connection->quoteTable('db.table');
    $this->assertEquals( '"db"."table"', $quoted );
  }

}
