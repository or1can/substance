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

namespace Substance\Core\Alert\Alerts;

use Substance\Core\Alert\Alert;

/**
 * An Alert that wraps an Exception, so that it can be easily presented.
 */
class ExceptionWrapper extends Alert {

  /**
   * Construct a new alert.
   *
   * @param string $message the alert message.
   * @param string $explanation the alert explanation.
   * @param number $code the alert code.
   * @param string $previous the previous exception in the chain.
   */
  public function __construct( \Exception $ex ) {
    parent::__construct( get_class( $ex ), $ex->getMessage(), $ex->getCode(), $ex );
  }

  /**
   * Returns the file where the alert occurred. This should really be an
   * override of getFile(), but PHP helpfully declares getFile() as final.
   *
   * @return string the file the alert occurred in
   */
  public function getAlertFile() {
    return parent::getPrevious()->getFile();
  }

  /**
   * Returns the line number the alert occurred at. This should really be an
   * override of getLine(), but PHP helpfully declares getLine() as final.
   *
   * @return int the line number the alert occurred at
   */
  public function getAlertLine() {
    return parent::getPrevious()->getLine();
  }

  /**
   * Returns the alert trace. This should really be an override of getTrace(),
   * but PHP helpfully declares getMessage() as final.
   *
   * @return string the alert information.
   */
  public function getAlertTrace() {
    $trace = parent::getPrevious()->getTrace();
    // FIXME - Do we need to support this?
    if ( $this->constructed_in_alert ) {
      array_shift( $trace );
    }
    return $trace;
  }

}
