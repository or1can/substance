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

namespace Substance\Core\Presentation\Elements;

use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\Theme;

/**
 * A page element, i.e. a special Container for other elements.
 */
class Page extends Container {

  protected $title;

  protected $scripts;

  /**
   * The pages styles.
   *
   * @var Style[]
   */
  protected $styles = array();

  protected $headers;

  /**
   * Adds an Style to the container.
   *
   * @param Style ...$style the Style to add.
   * @return self this element so methods can be chained.
   */
  public function addStyle( Style $style ) {
    for ( $i = 0; $i < func_num_args(); $i++ ) {
      $elem = func_get_arg( $i );
      if ( $elem instanceof Style ) {
        $this->styles[] = $elem;
      } else {
        throw new \InvalidArgumentException('Can only add Styles to the page style');
      }
    }
    return $this;
  }

  /**
   * Adds an array of Styles to the container.
   *
   * @param Style[] $styles the array of Styles to add.
   * @return self this element so methods can be chained.
   */
  public function addStyles( array $styles ) {
    call_user_func_array( array( $this, 'addStyle' ), $styles );
    return $this;
  }

  /**
   * Returns the containers styles.
   *
   * @return Style[] the containers styles.
   */
  public function getStyles() {
    return $this->styles;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Container::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderPage( $this );
  }

}
