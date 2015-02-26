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
 * Tests the datetime type schema element.
 */
class DateTimeTest extends AbstractSQLTest {

  /**
   * Test building an datetime.
   */
  public function testBuild() {
    $datetime = new DateTime();
    $this->assertEquals( 'DATETIME', $datetime->build( $this->connection ) );
  }

}