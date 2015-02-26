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
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\Schema\Types\Char;
use Substance\Core\Database\Schema\Types\VarChar;
use Substance\Core\Database\Schema\Types\Numeric;
use Substance\Core\Database\Schema\Types\Text;
use Substance\Core\Database\Schema\Types\Date;
use Substance\Core\Database\Schema\Types\DateTime;
use Substance\Core\Database\Schema\Types\Time;

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
    $this->assertEquals( 'CREATE TABLE `table` ()', $sql );

    // Add some columns
    $table->addColumnByName( 'col', new Integer() );
    $table->addColumnByName( 'col2', new Char( 5 ) );
    $table->addColumnByName( 'col3', new VarChar( 10 ) );
    $table->addColumnByName( 'col4', new Numeric( 10, 5 ) );
    $table->addColumnByName( 'col5', new Text() );
    $table->addColumnByName( 'col6', new Date() );
    $table->addColumnByName( 'col7', new DateTime() );
    $table->addColumnByName( 'col8', new Time() );
    $sql = $definition->build( $this->connection );
    $this->assertEquals( 'CREATE TABLE `table` (`col` INTEGER, `col2` CHAR(5), `col3` VARCHAR(10), `col4` NUMERIC(10, 5), `col5` TEXT, `col6` DATE, `col7` DATETIME, `col8` TIME)', $sql );

    $table = new BasicTable( $this->connection, 'table.dot' );
    $definition = new CreateTable( $this->connection, $table );
    $sql = $definition->build( $this->connection );
    $this->assertEquals( 'CREATE TABLE `table.dot` ()', $sql );
  }

}
