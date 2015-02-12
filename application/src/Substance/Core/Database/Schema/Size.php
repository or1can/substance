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

use Substance\Core\Alert\Alert;

/**
 * Represents the size of a data type..
 */
class Size {

  const __default = self::NORMAL;

  const TINY = 1;

  const SMALL = 2;

  const MEDIUM = 3;

  const NORMAL = 4;

  const BIG = 5;

  /**
   * @var array array of enum size to enum objects.
   */
  protected static $enums = array();

  /**
   * @var int the size value (one of the constants).
   */
  protected $value;

  protected function __construct( $size ) {
    if ( in_array( $size, $this->getConstList() ) ) {
      $this->value = $size;
    } else {
      throw Alert::alert( 'Invalid size', 'Size must be one of TINY, SMALL, MEDIUM, NORMAL or BIG' )
        ->culprit( 'size', $size );
    }
  }

  /**
   * Returns an array containing all the allowed values as an associative array
   * mapping constant name to value.
   *
   * @return array array of allowed values.
   */
  public function getConstList() {
    $class = new \ReflectionClass( $this );
    $constants = $class->getConstants();
    return array_diff_key( $constants, array( '__default' => '' ) );
  }

  /**
   * Returns the numerical representation for this size.
   *
   * Larger "sizes" have large numerical values.
   *
   * @return int the numerical representation for this size.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Returns the size object for the specified size.
   *
   * @param int $size the size to get.
   * @return Size the size object for the specified size.
   */
  public static function size( $size ) {
    if ( !array_key_exists( $size, self::$enums ) ) {
      self::$enums[ $size ] = new Size( $size );
    }
    return self::$enums[ $size ];
  }

}
