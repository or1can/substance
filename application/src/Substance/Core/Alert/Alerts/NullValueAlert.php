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

namespace Substance\Core\Alert\Alerts;

use Substance\Core\Alert\Alert;

/**
 * An Alert for unexpected NULL values.
 */
class NullValueAlert extends Alert {

  /**
   * Construct a new null value alert.
   *
   * @param string $explanation the alert explanation.
   * @param number $code the alert code.
   * @param string $previous the previous exception in the chain.
   */
  public function __construct( $explanation = '', $code = 0, \Exception $previous = NULL ) {
    parent::__construct( 'Unexpected NULL', $explanation, $code, $previous );
  }

  /**
   * Returns a new null value alert.
   *
   * This method is provided for convenience and for method chaining.
   *
   * @param string $explanation the alert explanation.
   * @param number $code the alert code.
   * @param string $previous the previous exception in the chain.
   * @return UnsupportedOperationAlert
   */
  public static function nullValue( $explanation = '', $code = 0, \Exception $previous = NULL ) {
    return new NullValueAlert( $explanation, $code, $previous );
  }

}
