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

namespace Substance\Themes\HTML;

use Substance\Core\Presentation\AbstractTheme;
use Substance\Core\Presentation\Elements\TextField;
use Substance\Core\Presentation\Elements\Container;

/**
 * A renderer renders elements.
 */
class HTMLTheme extends AbstractTheme {

  /**
   * @return self
   */
  public static function create() {
    return new HTMLTheme();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\AbstractTheme::renderContainer()
   */
  public function renderContainer( Container $container ) {
    $output = '<div>';
    foreach ( $container->getElements() as $element ) {
      $output .= $this->render( $element );
    }
    return $output . '</div>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Theme::renderTextField()
   */
  public function renderTextField( TextField $textfield ) {
    return '<div><input type="text" value="' . $textfield->getValue() . '" /></div>';
  }

}
