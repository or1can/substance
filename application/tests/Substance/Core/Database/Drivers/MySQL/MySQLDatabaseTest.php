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

use Substance\Core\Database\Schema\AbstractDatabaseTest;
use Substance\Core\Database\Schema\ColumnImpl;
use Substance\Core\Database\Schema\Size;
use Substance\Core\Database\Schema\Types\Float;
use Substance\Core\Database\Schema\Types\Integer;

/**
 * Base for MySQL database tests.
 */
class MySQLDatabaseTest extends AbstractDatabaseTest {

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

  /**
   * Test building a float for MySQL.
   */
  public function testBuildFloat() {
    $float = new Float( Size::size( Size::TINY ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'DOUBLE', $this->database->buildFloat( $float ) );
  }

  /**
   * Test building a integer for MySQL.
   */
  public function testBuildInteger() {
    $integer = new Integer( Size::size( Size::TINY ) );
    $this->assertEquals( 'TINYINT', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'SMALLINT', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'MEDIUMINT', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'BIGINT', $this->database->buildInteger( $integer ) );
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
