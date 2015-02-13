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

/**
 * Tests the size schema element.
 */
class SizeTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test the size value.
   */
  public function testGetValue() {
    $size = Size::size( Size::TINY );
    $this->assertEquals( 1, $size->getValue() );

    $size = Size::size( Size::SMALL );
    $this->assertEquals( 2, $size->getValue() );

    $size = Size::size( Size::MEDIUM );
    $this->assertEquals( 3, $size->getValue() );

    $size = Size::size( Size::NORMAL );
    $this->assertEquals( 4, $size->getValue() );

    $size = Size::size( Size::BIG );
    $this->assertEquals( 5, $size->getValue() );
  }

  /**
   * Test the size singleton.
   */
  public function testSize() {
    $size = Size::size( Size::TINY );
    $this->assertNotNull( $size );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\Size', $size );

    $size = Size::size( Size::SMALL );
    $this->assertNotNull( $size );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\Size', $size );

    $size = Size::size( Size::MEDIUM );
    $this->assertNotNull( $size );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\Size', $size );

    $size = Size::size( Size::NORMAL );
    $this->assertNotNull( $size );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\Size', $size );

    $size = Size::size( Size::BIG );
    $this->assertNotNull( $size );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\Size', $size );
  }

  /**
   * Test the size singleton with illegal values.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testSizeIllegal() {
    $size = Size::size( 0 );
  }

}
