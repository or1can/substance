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

namespace Substance\Core\Database\Schema\Types;

use Substance\Core\Database\SQL\AbstractSQLTest;

/**
 * Tests the varchar type schema element.
 */
class VarCharTest extends AbstractSQLTest {

  /**
   * Test building an varchar.
   */
  public function testBuild() {
    $varchar = new VarChar( 2 );
    $this->assertEquals( 'VARCHAR(2)', $varchar->build( $this->connection ) );
  }

  /**
   * Test getting an varchars length.
   */
  public function testGetLength() {
    $varchar = new VarChar( 2 );
    $this->assertEquals( 2, $varchar->getLength() );
  }

}