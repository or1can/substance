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

use Substance\Core\Environment\Environment;
use Substance\Core\Presentation\ElementBuilder;
use Substance\Core\Presentation\Elements\Markup;
use Substance\Core\Presentation\Elements\Table;
use Substance\Core\Presentation\Elements\TableRow;
use Substance\Core\Presentation\Elements\TableCell;
use Substance\Core\Presentation\Elements\TextField;
use Substance\Core\Presentation\Presentable;
use Substance\Themes\Text\TextTheme;

/**
 * An Alert handler, handles alerts and other errors.
 *
 * This is inspired by Whoops.
 *
 * @see http://filp.github.io/whoops/
 */
class AlertHandler {

  protected $not_registered = TRUE;

  /**
   * PHP error handler callback.
   *
   * @param int $errno The level of error raised.
   * @param string $errstr The error message.
   * @param string $errfile The filename the error was raised in.
   * @param int $errline The line number the error was raised at.
   * @param array $errcontext The active symbol table at the point the error.
   * occurred. This contains every variable in the scope the error was
   * triggered in. DO NOT modify this.
   * @return boolean FALSE if PHP should run the normal error handler
   */
  public function handleError( $errno, $errstr, $errfile, $errline, $errcontext ) {
    throw new ErrorAlert( $errno, $errstr, $errfile, $errline, $errcontext );
  }

  /**
   * PHP exception handler callback.
   *
   * @param \Exception $ex The uncaught exception.
   */
  public function handleException( \Exception $ex ) {
    echo $ex;
  }

  /**
   * Shutdown callback, used to catch fatal errors.
   */
  public function handleShutdown() {
    $error = error_get_last();
    if ( $error['type'] == E_ERROR ) {
      $alert = new ErrorAlert( $error['type'], $error['message'], $error['file'], $error['line'], array() );
      $this->handleException( $alert );
    }
  }

  /**
   * Register this handler, so it will handle all errors.
   */
  public function register() {
    if ( $this->not_registered ) {
      set_error_handler( array( $this, 'handleError' ) );
      set_exception_handler( array( $this, 'handleException' ) );
      register_shutdown_function( array( $this, 'handleShutdown' ) );
      $this->is_registered = FALSE;
    }
  }

}
