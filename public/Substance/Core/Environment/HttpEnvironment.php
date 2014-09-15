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

use Substance\Themes\TwigHtml\TwigHTMLTheme;

/**
 * An HTTP specific environment.
 */
class HttpEnvironment extends Environment {

  /* (non-PHPdoc)
   * @see \Substance\Core\Environment\Environment::initialise()
   */
  public static function initialise() {
    $environment = new HttpEnvironment();
    Environment::setEnvironment( $environment );
    $environment->setOutputTheme( TwigHTMLTheme::create() );
  }

}
