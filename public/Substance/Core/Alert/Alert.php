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
 * An Alert, our Exception on steroids. The more information you can pack in
 * when something goes wrong, the easier it is for someone to understand what's
 * gone wrong - give developers and users a hand.
 *
 * This Alert is heavily based on the Alert used in MillScript, but I've had to
 * cut out lots of bits converting from Java.
 */
class Alert extends \Exception {

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
  public function __construct( $message, $explanation = '', $code = 0, $previous = NULL ) {
    parent::__construct( $message, $code, $previous );
    $this->explanation = $explanation;
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
   * Returns the alert information. This should really be an override of
   * getMessage(), but PHP helpfully declares getMessage() as final.
   *
   * @return string the alert information.
   */
  public function getInfo() {
    $info = '';
    foreach ( $this->culprits as $culprit ) {
      $info .= $culprit;
      $info .= PHP_EOL; // FIXME - Make this a configuration option, as it may vary in Web context?
    }
    return $info;
  }

  /**
   * Reports this alert to the specified reporter.
   *
   * @param AlertReporter $reporter the AlertReporter to report this alert to.
   */
  public function report( AlertReporter $reporter ) {
    $reporter.report( $this );
  }

  /* (non-PHPdoc)
   * @see Exception::__toString()
   */
  public function __toString() {
    return $this->getMessage() . PHP_EOL . $this->getInfo();
  }

}
