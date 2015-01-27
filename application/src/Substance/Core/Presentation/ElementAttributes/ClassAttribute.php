<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
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

namespace Substance\Core\Presentation\ElementAttributes;

use Substance\Core\Presentation\ElementAttribute;

/**
 * The ClassAttribute is an ElementAttriute that represents the HTML class
 * attribute, i.e. an attribute that has multiple values separated by spaces.
 */
class ClassAttribute extends ElementAttribute {

  /**
   * The set of classes for this class attribute.
   *
   * @var array
   */
  protected $classes = array();

  public function __construct() {
    parent::__construct( 'class' );
  }

  /**
   * Clears the specified class.
   *
   * @param string $class the class to clear. More than one class can be passed
   * at a time.
   * @return self this element so methods can be chained.
   */
  public function clearClass( $class ) {
    $this->classes = array_diff_key( $this->classes, array_flip( func_get_args() ) );
    return $this;
  }

  /**
   * Returns this attributes value.
   *
   * @return string the attributes value.
   */
  public function getValue() {
    return implode( ' ', array_keys( $this->classes ) );
  }

  /**
   * Set the specified class. This will only add a specified class once.
   *
   * @param string ...$class the class the set. More than one class can be
   * passed at a time.
   * @return self this element so methods can be chained.
   */
  public function setClass( $class ) {
    $this->classes += array_flip( func_get_args() );
    return $this;
  }

}
