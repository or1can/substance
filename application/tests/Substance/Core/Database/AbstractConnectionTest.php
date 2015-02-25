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

namespace Substance\Core\Database;

/**
 * Base for  connection tests.
 */
abstract class AbstractConnectionTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Connection the database connection for testing
   */
  protected $connection;

  protected $test_database_names = array( 'test' );

  abstract public function initialise();

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->initialise();
  }

  /**
   * Test creating a database.
   */
  public function testCreateDatabase() {
    $test_db_name = $this->test_database_names[ 0 ];
    $test = $this->connection->createDatabase( $test_db_name );
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\MySQL\MySQLDatabase', $test );
    $this->assertEquals( $test_db_name, $test->getName() );
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
    $this->assertNotEquals( 'test', $this->connection->getActiveDatabaseName() );
    $this->connection->setActiveDatabaseName('test');
    $this->assertEquals( 'test', $this->connection->getActiveDatabaseName() );
  }

}
