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

  /**
   * Test the active database name.
   */
  public function testGetActiveDatabaseName() {
    $this->assertEquals( 'main', $this->connection->getActiveDatabaseName() );
  }

  /**
   * Test getting a database.
   */
  public function testGetDatabase() {
    $database = $this->connection->getDatabase();
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\SQLite\SQLiteDatabase', $database );
  }

  /**
   * Test checking for a database by name.
   */
  public function testHasDatabaseByName() {
    $this->assertTrue( $this->connection->hasDatabaseByName('main') );
    $this->assertFalse( $this->connection->hasDatabaseByName('test') );
  }

  /**
   * Test executing a SQL query.
   */
  public function testExecute() {
    $result = $this->connection->execute('SELECT 1');
    $this->assertTrue( is_int( $result ) );
  }

  /**
   * Test preparing a SQL query.
   */
  public function testPrepare() {
    $result = $this->connection->prepare('SELECT 1');
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
  }

  /**
   * Test executing a SQL query.
   */
  public function testQuery() {
    $result = $this->connection->query('SELECT 1');
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
  }

  /**
   * Test setting the active database name.
   */
  public function testSetActiveDatabaseName() {
    $this->assertEquals( 'main', $this->connection->getActiveDatabaseName() );
    $this->connection->setActiveDatabaseName('test');
    $this->assertEquals( 'test', $this->connection->getActiveDatabaseName() );
  }

}
