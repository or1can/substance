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

namespace Substance\Core\Alert;

/**
 * An Alert for wrapping PHP errors. This allows us to turn errors into Alerts
 * and then handle everything as an exception in a common manner.
 */
class ErrorAlert extends Alert {

  /**
   * Error context.
   * @var array
   */
  protected $context;

  /**
   * File error occured in.
   * @var string
   */
  protected $file;

  /**
   * Line number the error occured at.
   * @var int
   */
  protected $line;

  /**
   * Error severity.
   * @var int
   */
  protected $severity;

  /**
   * Construct a new error alert.
   *
   * @param int $errno The level of error raised.
   * @param string $errstr The error message.
   * @param string $errfile The filename the error was raised in.
   * @param int $errline The line number the error was raised at.
   * @param array $errcontext The active symbol table at the point the error.
   * occurred. This contains every variable in the scope the error was
   * triggered in. DO NOT modify this.
   */
  public function __construct( $errno, $errstr, $errfile, $errline, $errcontext ) {
    parent::__construct( $errstr, '', 0 );
    $this->severity = $errno;
    $this->file = $errfile;
    $this->line = $errline;
    $this->context = $errcontext;
  }

  /**
   * Returns a new ErrorAlert built from the supplied error array. The error
   * array should have the same struture as that returned by error_get_last().
   * This method is provided for convenience and for method chaining.
   *
   * @param string $error the error to wrap in an Alert.
   * @return self
   */
  public static function errorAlert( array $error ) {
    $alert = new ErrorAlert( $error['type'], $error['message'], $error['file'], $error['line'], array() );
    $alert->constructed_in_alert = TRUE;
    return $alert;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Alert\Alert::getAlertFile()
   */
  public function getAlertFile() {
    return $this->file;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Alert\Alert::getAlertLine()
   */
  public function getAlertLine() {
    return $this->line;
  }

}
