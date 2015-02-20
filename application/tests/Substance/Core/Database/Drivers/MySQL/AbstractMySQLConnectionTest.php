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

/**
 * Base for MySQL connection tests.
 */
abstract class AbstractMySQLConnectionTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  protected $test_database_names = array( 'test' );

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
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

}
