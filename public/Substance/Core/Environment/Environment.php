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

namespace Substance\Core\Environment;

use Substance\Core\Alert\AlertHandler;
use Substance\Core\Folder;
use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\Presentable;
use Substance\Core\Presentation\Theme;
use Substance\Themes\HTML\HTMLTheme;
use Substance\Themes\Text\TextTheme;

/**
 * Substance uses its Environment to contain configuration that must be shared
 * througout the application.
 */
abstract class Environment {

  /**
   * @var AlertHandler
   */
  protected $alert_hander;

  /**
   * @var Folder
   */
  protected $application_root;

  /**
   * @var Environment
   */
  protected static $environment;

  /**
   * @var Theme
   */
  protected $output_theme;

  /**
   * @var Folder
   */
  protected $temp_folder;

  /**
   * Hidden constructor, as this class should not be instantiated directly. Use
   * the getEnvironment method instead.
   */
  protected function __construct() {
    // Set the alert handler
    $alert_handler = new AlertHandler();
    $alert_handler->register();
    $this->setAlertHandler( $alert_handler );
    // Set the application root to the public folder.
    $this->setApplicationRoot(
      new Folder( dirname( dirname( __DIR__ ) ) )
    );
    // Set the application temporary files folder
    $this->setApplicationTempFolder(
      new Folder( '/tmp/substance' )
    );
  }

  /**
   * Returns the applications alert handler.
   *
   * @return AlertHandler the alert handler.
   */
  public function getAlertHandler() {
    return $this->application_root;
  }

  /**
   * Returns the applications root folder.
   *
   * @return Folder the root folder.
   */
  public function getApplicationRoot() {
    return $this->application_root;
  }

  /**
   * Returns the applications temporary files folder.
   *
   * @return Folder the temporary files folder.
   */
  public function getApplicationTempFolder() {
    return $this->temp_folder;
  }

  /**
   * Returns the Environment for the application.
   *
   * An application can only have one Environment, which is created via this
   * method. The Environment can be modified, to customise the applications
   * behaviour. You can quickly initialise different environments by calling
   * the appropriate initialise method as soon as your application loads, or
   * by calling getEnvironment and making the appropriate changes.
   *
   * The default Environment is HTML based.
   *
   * @return \Substance\Core\Environment\Environment
   */
  public static function getEnvironment() {
    if ( is_null( self::$environment ) ) {
      if ( array_key_exists( 'SERVER_NAME', $_SERVER ) ) {
        HttpEnvironment::initialise();
      } else {
        CliEnvironment::initialise();
      }
    }
    return self::$environment;
  }

  /**
   * Returns the current output theme.
   *
   * @return Theme the output theme.
   */
  public function getOutputTheme() {
    return $this->output_theme;
  }

  /**
   * Initialise an environment, defaulting to an HttpEnvironment.
   */
  public static function initialise() {
    HttpEnvironment::initialise();
  }

  /**
   * Outputs the specified Element using the Environments output Theme.
   *
   * @param Element $element the element to output.
   */
  public function outputElement( Element $element ) {
    echo $this->renderElement( $element );
  }

  /**
   * Outputs the specified Presentable object using the Environments output
   * Theme.
   *
   * @param Presentable $presentable the presentable object to output.
   */
  public function outputPresentable( Presentable $presentable ) {
    echo $this->renderPresentable( $presentable );
  }

  /**
   * Returns a string containing the Element presented in the current output
   * Theme.
   *
   * @param Element $element the Element to output.
   * @return string a string containing the element rendered in the output
   * Theme.
   */
  public function renderElement( Element $element ) {
    return $this->getOutputTheme()->render( $element );
  }

  /**
   * Returns a string containing the Presentable object presented in the
   * current output Theme.
   *
   * @param Presentable $presentable the presentable object to output.
   * @return string a string containing the object presented in the output
   * Theme.
   */
  public function renderPresentable( Presentable $presentable ) {
    return $this->getOutputTheme()->renderPresentable( $presentable );
  }

  /**
   * Sets the alert handler to be used as this Environments alert handler.
   *
   * @param AlertHandler $alert_handler the applications alert handler.
   */
  public function setAlertHandler( AlertHandler $alert_handler ) {
    $this->alert_hander = $alert_handler;
  }

  /**
   * Sets the Folder to be used for this Environments application root.
   *
   * @param Folder $folder the applications root folder.
   */
  public function setApplicationRoot( Folder $folder ) {
    $this->application_root = $folder;
  }

  /**
   * Sets the Folder to be used for this Environments temporary files.
   *
   * @param Folder $folder the applications temporary files folder.
   */
  public function setApplicationTempFolder( Folder $folder ) {
    $this->temp_folder = $folder;
  }

  /**
   * Sets the Environment.
   *
   * This method allows some scope for other applications to create their own
   * environments and have them used by the Substance framework. It also allows
   * for multiple Environments to be used
   *
   * @param Environment $environment the Environment to use.
   */
  protected static function setEnvironment( Environment $environment ) {
    self::$environment = $environment;
  }

  /**
   * Sets the Theme to be used for this Environments output.
   *
   * @param Theme $theme the Theme to use for output.
   */
  public function setOutputTheme( Theme $theme ) {
    $this->output_theme = $theme;
  }

}
