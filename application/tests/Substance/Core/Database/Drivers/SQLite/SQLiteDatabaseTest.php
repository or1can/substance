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

use Substance\Core\Database\Schema\AbstractDatabaseTest;

/**
 * Base for SQLite database tests.
 */
class SQLiteDatabaseTest extends AbstractDatabaseTest {

  /* (non-PHPdoc)
   * @see AbstractConnectionTest::initialise()
   */
  public function initialise() {
    // Remove known test databases.
    if ( !file_exists('/tmp/substance') ) {
      mkdir('/tmp/substance');
    }
    $clear_databases = $this->test_database_names;
    $clear_databases[] = 'mydb';
    foreach ( $clear_databases as $database ) {
      $db = "/tmp/substance/$database.db";
      if ( file_exists( $db ) ) {
        $this->assertTrue( unlink("/tmp/substance/$database.db"), 'Failed to remove old SQLite database.' );
      }
    }
    // Open the test database.
    $this->connection = new SQLiteConnection('/tmp/substance/mydb.db');
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
