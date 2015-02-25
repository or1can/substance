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

use Substance\Core\Database\Schema\Size;
use Substance\Core\Database\SQL\AbstractSQLTest;

/**
 * Tests the float type schema element.
 */
class FloatTest extends AbstractSQLTest {

  /**
   * Test building an float.
   */
  public function testBuild() {
    $float = new Float();
    $this->assertEquals( 'FLOAT', $float->build( $this->connection ) );
  }

  /**
   * Test getting an floats size.
   */
  public function testGetSize() {
    $float = new Float();
    $this->assertSame( Size::size( Size::NORMAL ), $float->getSize() );
  }

  /**
   * Test setting an floats size.
   */
  public function testSetSize() {
    $float = new Float();
    $float->setSize( Size::size( Size::SMALL ) );
    $this->assertSame( Size::size( Size::SMALL ), $float->getSize() );
  }

  /**
   * Test setting an floats size with null.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testSetSizeNull() {
    $size = Size::size( NULL );
  }

}
