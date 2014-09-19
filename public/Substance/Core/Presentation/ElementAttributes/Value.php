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
 * The Value is an ElementAttriute that represents an attribute of an Element.
 * For example, a id attribute on a form element.
 */
class Value extends ElementAttribute {

  /**
   * The attribute value.
   *
   * @var string
   */
  protected $value;

  public function __construct( $name, $value ) {
    parent::__construct( $name );
    $this->value = $value;
  }

  /**
   * Returns this attributes value.
   *
   * @return string the attributes value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Set the attributes value.
   *
   * @param string $name the attributes value
   * @return self this element so methods can be chained.
   */
  public function setValue( $value ) {
    $this->value = $value;
    return $this;
  }

}
