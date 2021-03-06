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
use Substance\Core\Database\Schema\Type;
use Substance\Core\Alert\Alerts\NullValueAlert;

/**
 * An implementation of the common parts of the Type interface.
 */
abstract class AbstractType implements Type {

  /**
   * @var Size the float size.
   */
  protected $size;

  /**
   * Constructs a new float type.
   *
   * @param Size $size the float size or NULL for the default
   */
  public function __construct( Size $size = NULL ) {
    if ( is_null( $size ) ) {
      $this->size = Size::size( Size::NORMAL );
    } else {
      $this->size = $size;
    }
  }

  /**
   * Returns this integers size.
   *
   * @return Size this integers size.
   */
  public function getSize() {
    return $this->size;
  }

  /**
   * Sets this integers size.
   *
   * @param Size $size the size of this integer.
   */
  public function setSize( Size $size ) {
    if ( is_null( $size ) ) {
      throw NullValueAlert::nullValue('Supplied size must be an instance of Size');
    }
    $this->size = $size;
  }

}
