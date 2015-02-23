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

namespace Substance\Core\Database\SQL\DataDefinitions;

use Substance\Core\Database\SQL\AbstractSQLTest;
use Substance\Core\Database\Schema\BasicTable;

/**
 * Tests the create table data definition.
 */
class CreateTableTest extends AbstractSQLTest {

  /**
   * Test the build with simple table names.
   */
  public function testBuild() {
    $table = new BasicTable( $this->connection, 'table' );
    $definition = new CreateTable( $this->connection, $table );
    $sql = $definition->build( $this->connection );
    $this->assertEquals( 'CREATE TABLE `table` ( )', $sql );

    $table = new BasicTable( $this->connection, 'table.dot' );
    $definition = new CreateTable( $this->connection, $table );
    $sql = $definition->build( $this->connection );
    $this->assertEquals( 'CREATE TABLE `table.dot` ( )', $sql );
  }

}
