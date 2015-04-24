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

namespace Substance\Core\Util\CommandLine;

use Substance\Core\Alert\Alerts\IllegalValueAlert;

/**
 * Represents a command line option that does not require an argument.
 */
class Option {

  /**
   * @var string the help string for this option.
   */
  protected $help;

  /**
   * @var string the short variant of this option.
   */
  protected $short;

  /**
   * Constructs a new option with the specified short and long variant.
   *
   * @param string $short the short option character.
   * @param string $help help text for this option.
   */
  public function __construct( $short, $help = NULL ) {
    // Check short option is a single character.
    if ( strlen( $short ) == 1 ) {
      $this->short = '-' . $short;
    } else {
      throw IllegalValueAlert::illegal_value('Short option must be a single character')
        ->culprit( 'short option', $short );
    }
    $this->setHelp( $help );
  }

  /**
   * Returns this options help.
   *
   * @return string the help for this option.
   */
  public function getHelp() {
    return $this->help;
  }

  /**
   * Returns this options short variant.
   *
   * @return string the short variant for this option.
   */
  public function getShort() {
    return $this->short;
  }

  /**
   * Sets this options help.
   *
   * @param string $help the help for this option.
   */
  public function setHelp( $help ) {
    $this->help = $help;
  }

}
