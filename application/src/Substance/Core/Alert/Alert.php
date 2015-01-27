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
 * An Alert, our Exception on steroids. The more information you can pack in
 * when something goes wrong, the easier it is for someone to understand what's
 * gone wrong - give developers and users a hand.
 *
 * This Alert is heavily based on the Alert used in MillScript, but I've had to
 * cut out lots of bits converting from Java.
 */
class Alert extends \Exception implements Presentable {

  /**
   * This indicates if the alert was constructed in the alert, e.g. in a static
   * method like Alert::alert(). This matters because the location of the
   * new Alert() determines the Exception's origin.
   * @var boolean
   */
  protected $constructed_in_alert = FALSE;

  /**
   * This alerts culprits.
   *
   * @var \Substance\Core\Alert\Culprit[]
   */
  protected $culprits = array();

  /**
   * This alerts decorators.
   *
   * @var \Substance\Core\Alert\AlertDecorator[]
   */
  protected $decorators = array();

  /**
   * The explanation for this alert.
   *
   * @var string
   */
  protected $explanation;

  /**
   * Construct a new alert.
   *
   * @param string $message the alert message.
   * @param string $explanation the alert explanation.
   * @param number $code the alert code.
   * @param string $previous the previous exception in the chain.
   */
  public function __construct( $message, $explanation = '', $code = 0, \Exception $previous = NULL ) {
    parent::__construct( $message, $code, $previous );
    $this->explanation = $explanation;
  }

  /**
   * Returns a new Alert. This method is an alertnative to the constructor, so
   * you can neatly chain methods.
   *
   * @param string $message the alert message.
   * @param string $explanation the alert explanation.
   * @param number $code the alert code.
   * @param string $previous the previous exception in the chain.
   * @return self
   */
  public static function alert( $message, $explanation = '', $code = 0, $previous = NULL ) {
    $alert = new Alert( $message, $explanation, $code, $previous );
    $alert->constructed_in_alert = TRUE;
    return $alert;
  }

  /* (non-PHPdoc)
   * @see Exception::__toString()
   */
  public function __toString() {
    try {
      return Environment::getEnvironment()->renderPresentable( $this );
    } catch ( \Exception $ex ) {
      return 'INTERNAL ALERT ERROR:' . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString() . PHP_EOL . parent::__toString();
    }
  }

  /**
   * Appends a culprit to this alert.
   *
   * @param string $type the culprit type
   * @param string $value the culprit value
   * @return self
   */
  public function culprit( $type, $value ) {
    $this->culprits[] = new Culprit( $type, $value );
    return $this;
  }

  /**
   * Decorates this alert with information from the supplied decorator.
   *
   * @param AlertDecorator $decorator the decorator to decorate this alert
   * with.
   * @return self
   */
  public function decorate( AlertDecorator $decorator ) {
    if ( !in_array( $decorator, $this->decorators ) ) {
      $decorator.decorate( $this );
      $this->decorators[] = $decorator;
    }
    return $this;
  }

  /**
   * Returns the file where the alert occurred. This should really be an
   * override of getFile(), but PHP helpfully declares getFile() as final.
   *
   * @return string the file the alert occurred in
   */
  public function getAlertFile() {
    return parent::getFile();
  }

  /**
   * Returns the line number the alert occurred at. This should really be an
   * override of getLine(), but PHP helpfully declares getLine() as final.
   *
   * @return int the line number the alert occurred at
   */
  public function getAlertLine() {
    return parent::getLine();
  }

  /**
   * Returns the alert message. This should really be an override of
   * getMessage(), but PHP helpfully declares getMessage() as final.
   *
   * @return string the alert information.
   */
  public function getAlertMessage() {
    $info = '';
    foreach ( $this->culprits as $culprit ) {
      $info .= $culprit;
      $info .= PHP_EOL; // FIXME - Make this a configuration option, as it may vary in Web context?
    }
    return $info;
  }

  /**
   * Returns the alert trace. This should really be an override of getTrace(),
   * but PHP helpfully declares getMessage() as final.
   *
   * @return string the alert information.
   */
  public function getAlertTrace() {
    $trace = $this->getTrace();
    if ( $this->constructed_in_alert ) {
      array_shift( $trace );
    }
    return $trace;
  }

  /**
   * Returns the culprits for this alert.
   *
   * @return \Substance\Core\Alert\Culprit[] the alert culprits.
   */
  public function getCulprits() {
    return $this->culprits;
  }

  /**
   * Returns the explanation for this alert.
   *
   * @return string the alert explanation.
   */
  public function getExplanation() {
    return $this->explanation;
  }

  /**
   * Returns an origin message. An origin is a combination of file and line
   * number information, with the line number appened in parenthesis after the
   * file name.
   *
   * @return string the origin message
   */
  public function getOrigin() {
    $file = $this->getAlertFile();
    $line = $this->getAlertLine();
    if ( $this->constructed_in_alert ) {
      $trace = $this->getTrace();
      $trace = array_shift( $trace );
      $file = $trace['file'];
      $line = $trace['line'];
    }
    return "$file($line)";
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Presentable::present()
   */
  public function present() {
    $rows = array(
      array( 'Message', $this->getMessage() ),
    );
    if ( isset( $this->explanation ) && $this->explanation != '' ) {
      $rows[] = array( 'Explanation', $this->explanation );
    }
    foreach ( $this->culprits as $culprit ) {
      $rows[] = array(
        mb_strtoupper( $culprit->getType() ),
        $culprit->getValue(),
      );
    }
    $rows[] = array(
      'Origin',
      $this->getOrigin(),
    );
    foreach ( $this->getAlertTrace() as $index => $trace ) {
      $method = array_key_exists( 'file', $trace ) ? $trace['file'] : 'unknown file';
      $method .= '(';
      $method .= array_key_exists( 'line', $trace ) ? $trace['line'] : 'unkown line';
      $method .= '): ';
      if ( $trace['class'] != '' ) {
        $method .= $trace['class'];
        $method .= $trace['type'];
      }
      $method .= $trace['function'];
      $method .= '(';
      if ( $trace['args'] ) {
        $method .= implode(
          ', ',
          array_map(
            function( $value ) {
              if ( is_object( $value ) && !method_exists( $value, '__toString' ) ) {
                return 'CLASS[' . get_class( $value ) . ']';
              } else if ( is_array( $value ) ) {
                return 'Array';
              } else {
           	    return (string) $value;
              }
            },
            $trace['args']
          )
        );
      }
      $method .= ')';
      $rows[] = array(
        'Trace #' . $index,
        $method,
      );
    }
    $table = ElementBuilder::build(array(
      '#type' => 'Substance\Core\Presentation\Elements\Table',
      '#rows' => $rows,
    ));
    return $table;
  }

  /**
   * Reports this alert to the specified reporter.
   *
   * @param AlertReporter $reporter the AlertReporter to report this alert to.
   */
  public function report( AlertReporter $reporter ) {
    $reporter->report( $this );
  }

}
