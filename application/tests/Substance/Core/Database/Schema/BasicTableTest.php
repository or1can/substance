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

namespace Substance\Core\Database\Schema;

use Substance\Core\Database\Drivers\Unconnected\UnconnectedConnection;
use Substance\Core\Database\Drivers\Unconnected\UnconnectedDatabase;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\SQL\AbstractSQLTest;

/**
 * Tests the integer type schema element.
 */
class BasicTableTest extends AbstractSQLTest {

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\AbstractSQLTest::setUp()
   */
  public function setUp() {
    $connection = new UnconnectedConnection();
    $this->connection = $connection->getDatabase();
  }

  /**
   * Tests adding a table column.
   */
  public function testAddColumn() {
    // Create a table.
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertCount( 0, $table->listColumns() );
    // Add a column.
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $table->addColumn( $column );
    // Check it contains the column.
    $this->assertCount( 1, $table->listColumns() );
    $this->assertTrue( $table->hasColumnByName('col') );
  }

  /**
   * Tests adding a table column by name.
   */
  public function testAddColumnByName() {
    // Create a table.
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertCount( 0, $table->listColumns() );
    // Add a column
    $table->addColumnByName( 'col', new Integer() );
    // Check it contains the column.
    $this->assertCount( 1, $table->listColumns() );
    $this->assertTrue( $table->hasColumnByName('col') );
  }

  /**
   * Tests adding a table index.
   */
  public function testAddIndex() {
    // FIXME
  }

  /**
   * Tests dropping a table name.
   *
   * @expectedException Substance\Core\Alert\Alerts\UnsupportedOperationAlert
   */
  public function testDrop() {
    // Check there are no tables.
    $this->assertCount( 0, $this->connection->listTables() );
    // Create a table.
    $table = new BasicTable( $this->connection, 'table' );
    $table->addColumnByName( 'col2', new Integer() );
    // Check the table exists.
    $this->assertCount( 1, $this->connection->listTables() );
    $this->assertArrayHasKey( $this->connection->hasTableByName('table') );
    // Drop the table.
    $table->drop();
    $this->connection->applyDataDefinitions();
    // Check there are no tables.
    $this->assertCount( 0, $this->connection->listTables() );
  }

  /**
   * Tests getting a table column.
   */
  public function testGetColumn() {
    // FIXME
  }

  /**
   * Tests getting a table column that doesn't exist.
   *
   * @expectedException Substance\Core\Database\Alerts\DatabaseAlert
   */
  public function testGetColumnNotExist() {
    $table = new BasicTable( $this->connection, 'table' );
    $table->getColumn('col');
  }

  /**
   * Tests getting a tables parent database.
   */
  public function testGetDatabase() {
    $database = $this->connection->getConnection()->getDatabase();
    $this->assertInstanceOf( 'Substance\Core\Database\Drivers\Unconnected\UnconnectedDatabase', $database );
  }

  /**
   * Tests getting a table index.
   */
  public function testGetIndex() {
    // FIXME
  }

  /**
   * Tests getting a table index that doesn't exist.
   *
   * @expectedException Substance\Core\Database\Alerts\DatabaseAlert
   */
  public function testGetIndexNotExist() {
    $table = new BasicTable( $this->connection, 'table' );
    $table->getIndex('index');
  }

  /**
   * Tests getting a table name.
   */
  public function testGetName() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertEquals( 'table', $table->getName() );
  }

  /**
   * Tests checking a table has a column by name.
   */
  public function testHasColumnByName() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertFalse( $table->hasColumnByName('col') );
    // TODO - Add a column and check again
  }

  /**
   * Tests checking a table has an index by name.
   */
  public function testHasIndexByName() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertFalse( $table->hasIndexByName('index') );
    // TODO - Add an index and check again
  }

  /**
   * Tests listing a tables columns.
   */
  public function testListColumns() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertCount( 0, $table->listColumns() );
    // TODO - Add a column and check again
  }

  /**
   * Tests listing a tables indexes.
   */
  public function testListIndexes() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertCount( 0, $table->listIndexes() );
    // TODO - Add an index and check again
  }

  /**
   * Tests setting a tables name.
   */
  public function testSetName() {
    $table = new BasicTable( $this->connection, 'table' );
    $this->assertEquals( 'table', $table->getName() );
    $table->setName('sample');
    $this->assertEquals( 'sample', $table->getName() );
  }

}
