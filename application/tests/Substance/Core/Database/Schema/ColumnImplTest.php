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

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\Schema\Types\Integer;

/**
 * Tests the column schema element.
 */
class ColumnImplTest extends AbstractSQLTest {

  /**
   * Tests constructing a ColumnImpl with various default values.
   */
  public function testConstructDefault() {
    $table = new BasicTable( $this->connection, 'table' );
    new ColumnImpl( $table, 'col', new Integer(), NULL );
    new ColumnImpl( $table, 'col', new Integer(), TRUE );
    new ColumnImpl( $table, 'col', new Integer(), 'string' );
    new ColumnImpl( $table, 'col', new Integer(), 5 );
    new ColumnImpl( $table, 'col', new Integer(), 4.29723 );
  }

  /**
   * Tests constructing a ColumnImpl with an illegal default.
   *
   * @expectedException Substance\Core\Alert\Alerts\IllegalValueAlert
   */
  public function testConstructDefaultIllegal() {
    $table = new BasicTable( $this->connection, 'table' );
    new ColumnImpl( $table, 'col', new Integer(), $table );
  }

  /**
   * Tests a colummn allowing null values.
   */
  public function testAllowsNull() {
    $table = new BasicTable( $this->connection, 'table' );
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $this->assertTrue( $column->allowsNull() );
  }

}
