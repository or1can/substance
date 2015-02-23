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

use Substance\Core\Database\AbstractConnectionTest;

/**
 * Base for MySQL connection tests.
 */
class MySQLConnectionTest extends AbstractConnectionTest {

  /* (non-PHPdoc)
   * @see AbstractConnectionTest::initialise()
   */
  public function initialise() {
    $this->connection = new MySQLConnection( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
    // Clear out mydb.
    $sql = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'mydb'";
    foreach ( $this->connection->query( $sql ) as $row ) {
      $this->connection->execute( 'DROP TABLE ' . $this->connection->quoteTable( $row->TABLE_NAME ) );
    }
    // Remove known test databases.
    foreach ( $this->test_database_names as $database ) {
      $this->connection->execute( 'DROP DATABASE IF EXISTS' . $this->connection->quoteName( $database ) );
    }
  }

  public function testCreateDatabase() {
    $test_db_name = $this->test_database_names[ 0 ];
    $test = $this->connection->createDatabase( $test_db_name );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $test );
    $this->assertEquals( $test_db_name, $test->getName() );
  }

  /**
   * Test the active database name.
   */
  public function testGetActiveDatabaseName() {
    $this->assertEquals( 'mydb', $this->connection->getActiveDatabaseName() );
  }

  /**
   * Test getting a database.
   */
  public function testGetDatabase() {
    $database = $this->connection->getDatabase();
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $database );
  }

  /**
   * Test checking for a database by name.
   */
  public function testHasDatabaseByName() {
    $this->assertTrue( $this->connection->hasDatabaseByName('mydb') );
    $this->assertFalse( $this->connection->hasDatabaseByName('test') );
  }

  public function testListDatabases() {
    $databases = $this->connection->listDatabases();
    $this->assertTrue( is_array( $databases ) );
    $this->assertCount( 1, $databases );
    $this->assertArrayHasKey( 'mydb', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $databases['mydb'] );

    // Create a new database and check it is listed now.
    $test_db_name = $this->test_database_names[ 0 ];
    $this->connection->createDatabase( $test_db_name );
    $databases = $this->connection->listDatabases();
    $this->assertTrue( is_array( $databases ) );
    $this->assertCount( 2, $databases );
    $this->assertArrayHasKey( 'mydb', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $databases['mydb'] );
    $this->assertArrayHasKey( 'test', $databases );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $databases['test'] );
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '`', $this->connection->quoteChar() );
  }

  /**
   * Test quoting names.
   */
  public function testQuoteName() {
    // Test a simple name.
    $quoted = $this->connection->quoteName('table');
    $this->assertEquals( '`table`', $quoted );

    // Test a name with a period.
    $quoted = $this->connection->quoteName('db.table');
    $this->assertEquals( '`db.table`', $quoted );

    // Test a name with a quote character.
    $quoted = $this->connection->quoteName('db`table');
    $this->assertEquals( '`db``table`', $quoted );
  }

  /**
   * Test quoting strings.
   */
  public function testQuoteString() {
    // Test a simple string.
    $quoted = $this->connection->quoteString('string');
    $this->assertEquals( '\'string\'', $quoted );

    // Test a string with a quote character.
    $quoted = $this->connection->quoteString('db\'table');
    $this->assertEquals( '\'db\\\'table\'', $quoted );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->connection->quoteTable('table');
    $this->assertEquals( '`table`', $quoted );

    // Test a table with database.
    $quoted = $this->connection->quoteTable('db.table');
    $this->assertEquals( '`db`.`table`', $quoted );
  }

}
