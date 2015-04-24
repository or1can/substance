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
use Substance\Core\Environment\Environment;
use Substance\Core\Presentation\ElementBuilder;
use Substance\Core\Presentation\Presentable;
use Substance\Core\Presentation\Elements\Container;
use Substance\Core\Presentation\Elements\Markup;

/**
 * Represents a set of command line options.
 */
class Options implements Presentable {

  /**
   * @var Options the single instance of this connection.
   */
  private static $instance;

  /**
   * @var array array of short command line options.
   */
  public $short_options = array();

  /* (non-PHPdoc)
   * @see Exception::__toString()
   */
  public function __toString() {
    try {
      return Environment::getEnvironment()->renderPresentable( $this );
    } catch ( \Exception $ex ) {
      return 'INTERNAL OPTIONS ERROR:' . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString() . PHP_EOL . parent::__toString();
    }
  }

  /**
   * Adds a new option to the supported set.
   *
   * @param Option $option the command line option to add.
   */
  public function add( Option $option ) {
    // Check short option has not already been declared.
    if ( array_key_exists( $option->getShort(), $this->short_options ) ) {
      throw IllegalValueAlert::illegal_value('Short option has already been declared')
        ->culprit( 'short option', $option->getShort() );
    } else {
      $this->short_options[ $option->getShort() ] = $option;
    }
  }

  /**
   * Creates a new command line option and defines it for use.
   *
   * @param string $short the short option character.
   * @param string $help help text for this option.
   * @return self the new command line option.
   */
  public function create( $short, $help = NULL ) {
    $option = new Option( $short, $help );
    $this->add( $option );
    return $option;
  }

  /**
   * Returns a unique instance of this options.
   *
   * @return self
   */
  public static function getInstance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new Options();
    }
    return self::$instance;
  }

  /**
   * Returns this options short variant.
   *
   * @return string the short variant for this option.
   */
  public function getShortOptions() {
    return $this->short_options;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Presentable::present()
   */
  public function present() {
    $container = Container::create();
    $container->addElement( Markup::build( 'Application' ) );
    $container->addElement( Markup::build( '===========' ) );
    $rows = array();
    foreach ( $this->short_options as $option ) {
      $rows[] = array(
        $option->getShort(),
        $option->getHelp()
      );
    }
    $table = ElementBuilder::build(array(
      '#type' => 'Substance\Core\Presentation\Elements\Table',
      '#rows' => $rows,
    ));
    $container->addElement( $table );
    return $container;
  }

}
