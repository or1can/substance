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

use Substance\Core\Alert\Alert;
use Substance\Core\Environment\Environment;
use Substance\Core\TestSettings;

/**
 * Common abstract record tests.
 */
class AbstractRecordTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var TestRecord the test record
   */
  protected $test_record;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    // Set up the test environment.
    Environment::getEnvironment()->setSettings( new TestSettings() );
    // Create the instance of the test record for our tests.
    $this->test_record = new TestRecord();
    // Ensure the database is in a known state.
    // First, drop any existing table.
    $connection = ConnectionFactory::getConnection();
    $database = $connection->getDatabase();
    if ( $database->hasTableByName( $this->test_record->backingStoreTableName() ) ) {
      $database->getTable( $this->test_record->backingStoreTableName() )->drop();
    }
  }

  /**
   * Test the backing store create.
   */
  public function testBackingStoreCreate() {
    $this->assertFalse( $this->test_record->backingStoreExists() );
    $this->test_record->backingStoreCreate();
    $this->assertTrue( $this->test_record->backingStoreExists() );
  }

  /**
   * Test the backing store table name.
   */
  public function testBackingStoreTableName() {
    $this->assertEquals( 'Substance_Core_Database_TestRecord', $this->test_record->backingStoreTableName() );
  }

  /**
   * Test finding a record.
   */
  public function testFind() {
    // Try finding in an empty table.
    $result = $this->test_record->find();
    $this->assertNull( $result );
    // Force some records in to the table
    // FIXME - Update to use our SQL API when we can.
    $connection = ConnectionFactory::getConnection();
    $connection->execute( "INSERT INTO Substance_Core_Database_TestRecord ( col1 ) VALUES ( 1 ), ( 2 )" );

    // Try finding in from a table with values.
    $result = $this->test_record->find();
    $this->assertInstanceOf( 'Substance\Core\Database\TestRecord', $result );
    $this->assertEquals( 1, $result->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->find( array( 'col1' => 1 ) );
    $this->assertInstanceOf( 'Substance\Core\Database\TestRecord', $result );
    $this->assertEquals( 1, $result->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->find( array( 'col1' => 2 ) );
    $this->assertInstanceOf( 'Substance\Core\Database\TestRecord', $result );
    $this->assertEquals( 2, $result->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->find( array( 'col1' => 3 ) );
    $this->assertNull( $result );
  }

  /**
   * Test finding all records.
   */
  public function testFindAll() {
    // Try finding in an empty table.
    $result = $this->test_record->findAll();
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 0, $found );

    // Force some records in to the table
    // FIXME - Update to use our SQL API when we can.
    $connection = ConnectionFactory::getConnection();
    $connection->execute( "INSERT INTO Substance_Core_Database_TestRecord ( col1 ) VALUES ( 1 ), ( 2 )" );

    // Try finding in from a table with values.
    $result = $this->test_record->findAll();
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 2, $found );
    $this->assertEquals( 1, $found[ 0 ]->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->findAll( array( 'col1' => 1 ) );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 1, $found );
    $this->assertEquals( 1, $found[ 0 ]->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->findAll( array( 'col1' => 2 ) );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 1, $found );
    $this->assertEquals( 2, $found[ 0 ]->col1 );

    // Try finding in from a table with values.
    $result = $this->test_record->findAll( array( 'col1' => 3 ) );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 0, $found );
  }

  /**
   * Test taking a record.
   */
  public function testTake() {
    // Try taking from an empty table.
    $result = $this->test_record->take();
    $this->assertNull( $result );
    // Force some records in to the table
    // FIXME - Update to use our SQL API when we can.
    $connection = ConnectionFactory::getConnection();
    $connection->execute( "INSERT INTO Substance_Core_Database_TestRecord ( col1 ) VALUES ( 1 ), ( 2 )" );

    // Try taking from a table with values.
    $result = $this->test_record->take();
    $this->assertInstanceOf( 'Substance\Core\Database\TestRecord', $result );
    $this->assertEquals( 1, $result->col1 );
  }

  /**
   * Test taking some records.
   */
  public function testTakeSome() {
    // Try taking from an empty table.
    $result = $this->test_record->takeSome( 0 );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 0, $found );
    $result = $this->test_record->takeSome( 5 );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 0, $found );

    // Force some records in to the table
    // FIXME - Update to use our SQL API when we can.
    $connection = ConnectionFactory::getConnection();
    $connection->execute( "INSERT INTO Substance_Core_Database_TestRecord ( col1 ) VALUES ( 1 ), ( 2 )" );

    // Try taking from a table with values.
    $result = $this->test_record->takeSome( 0 );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 0, $found );
    $result = $this->test_record->takeSome( 1 );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 1, $found );
    $this->assertEquals( 1, $found[ 0 ]->col1 );
    $result = $this->test_record->takeSome( 2 );
    $this->assertInstanceOf( 'Substance\Core\Database\Statement', $result );
    $found = $result->fetchAll( \PDO::FETCH_CLASS );
    $this->assertCount( 2, $found );
    $this->assertEquals( 1, $found[ 0 ]->col1 );
    $this->assertEquals( 2, $found[ 1 ]->col1 );
  }

}
