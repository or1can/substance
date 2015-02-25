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

namespace Substance\Core\Database\Drivers\Unconnected;

use Substance\Core\Database\Schema\AbstractDatabaseTest;
use Substance\Core\Database\Schema\ColumnImpl;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\Schema\Size;

/**
 * Base for Unconnected database tests.
 */
class UnconnectedDatabaseTest extends AbstractDatabaseTest {

  /* (non-PHPdoc)
   * @see AbstractConnectionTest::initialise()
   */
  public function initialise() {
    $this->connection = new UnconnectedConnection();
  }

  /**
   * @expectedException Substance\Core\Alert\Alerts\UnsupportedOperationAlert
   * @see \Substance\Core\Database\Schema\AbstractDatabaseTest::testCreateTable()
   */
  public function testCreateTable() {
    $table = $this->database->createTable('table');
  }

  /**
   * @expectedException Substance\Core\Alert\Alerts\UnsupportedOperationAlert
   * @see \Substance\Core\Database\Schema\AbstractDatabaseTest::testListTables()
   */
  public function testListTables() {
    $this->database->listTables();
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '', $this->database->quoteChar() );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->database->quoteTable('table');
    $this->assertEquals( 'table', $quoted );

    // Test a table with database.
    $quoted = $this->database->quoteTable('db.table');
    $this->assertEquals( 'db.table', $quoted );
  }

}
