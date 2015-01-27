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

namespace Substance\Core\Alert;

/**
 * An Alert Culprit, or a simple key-value pair of information that can be
 * attached to an Alert.
 */
class Culprit {

  /**
   * The culprit type.
   *
   * @var mixed
   */
  protected $type;

  /**
   * The culprit value.
   *
   * @var mixed
   */
  protected $value;

  /**
   * Construct a new culprit with the specified type and value.
   *
   * @param mixed $type the culprit type.
   * @param mixed $value the culprit value.
   */
  public function __construct( $type, $value ) {
    $this->type = $type;
    $this->value = $value;
  }

  /**
   * Returns the culprit type.
   *
   * @return mixed the culprit type.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Returns the culprit value.
   *
   * @return mixed the culprit value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @return string the string representation of this culprit.
   */
  public function __toString() {
    return mb_strtoupper( $this->type ) . " : $this->value";
  }

}
