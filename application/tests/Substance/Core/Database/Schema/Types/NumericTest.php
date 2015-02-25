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
 * Tests the numeric type schema element.
 */
class NumericTest extends AbstractSQLTest {

  /**
   * Test we can construct a numeric type.
   */
  public function testConstructor() {
    $numeric = new Numeric( 2, 1 );
  }

  /**
   * Test we cannot construct a numeric type with a scale larger than the
   * precision.
   *
   * @expectedException Substance\Core\Alert\Alerts\IllegalValueAlert
   */
  public function testConstructorScaleGreaterThanPrecision() {
    $numeric = new Numeric( 1, 2 );
  }

  /**
   * Test building an numeric.
   */
  public function testBuild() {
    $numeric = new Numeric( 2, 1 );
    $this->assertEquals( 'NUMERIC(2, 1)', $numeric->build( $this->connection ) );
  }

  /**
   * Test getting an numerics precision.
   */
  public function testGetSize() {
    $numeric = new Numeric( 2, 1 );
    $this->assertEquals( 2, $numeric->getPrecision() );
  }

  /**
   * Test getting an numerics scale.
   */
  public function testGetScale() {
    $numeric = new Numeric( 2, 1 );
    $this->assertEquals( 1, $numeric->getScale() );
  }

}