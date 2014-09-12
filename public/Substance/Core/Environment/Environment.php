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

use Substance\Core\Folder;
use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\Theme;
use Substance\Core\Presentation\Presentable;
use Substance\Themes\Text\TextTheme;
use Substance\Themes\HTML\HTMLTheme;

/**
 * Substance uses its Environment to contain configuration that must be shared
 * througout the application.
 */
class Environment {

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
   * Hidden constructor, as this class should not be instantiated directly. Use
   * the getEnvironment method instead.
   */
  private function __construct() {
    // Set the application root to the public folder.
    $this->setApplicationRoot(
      new Folder( dirname( dirname( __DIR__ ) ) )
    );
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
      self::$environment = self::initialiseHTMLEnvironment();
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
   * Initialise a plain text environment.
   *
   * This is used when your application is going to output primarily plain
   * text.
   */
  public static function initialiseTextEnvironment() {
    $environment = new Environment();
    $environment->setOutputTheme( TextTheme::create() );
    return $environment;
  }

  /**
   * Initialise an HTML environment.
   *
   * This is used when your application is going to output primarily HTML.
   */
  public static function initialiseHTMLEnvironment() {
    $environment = new Environment();
    $environment->setOutputTheme( HTMLTheme::create() );
    return $environment;
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
   * Sets the Folder to be used for this Environments application root.
   *
   * @param Folder $folder the applications root folder.
   */
  public function setApplicationRoot( Folder $folder ) {
    $this->application_root = $folder;
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
