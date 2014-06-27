<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2005 - 2014 Kevin Rogers
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

namespace Substance\Core\Presentation;

/**
 * The ValueElement is an Element that has a value that could be changed by
 * user input. For example, a text field on a form rather than a fieldset that
 * contains a collection of Elements.
 */
abstract class ValueElement extends Element {

  /**
   * The default value.
   *
   * @var mixed
   */
  protected $default_value = NULL;

  /**
   * Returns this elements default value.
   *
   * @return mixed the default value.
   */
  public function getDefaultValue() {
    return $this->default_value;
  }

  /**
   * Returns this elements current value. By default, this will return the
   * default value, but would be different if, for example, a different value
   * has been submitted in a form.
   *
   * @return mixed the current value.
   */
  public function getValue() {
    return $this->getDefaultValue();
  }

  /**
   * Set the default value for this element.
   *
   * @param mixed $default_value the default value
   * @return self this element so methods can be chained.
   */
  public function setDefaultValue( $default_value ) {
    $this->default_value = $default_value;
    return $this;
  }

}
