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
abstract class AbstractSQLiteConnectionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  protected $test_database_names = array( 'test' );

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
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

}
