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

namespace Substance\Core\Presentation\Elements;

use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\Theme;

/**
 * A simple markup element.
 */
class Markup extends Element {

  /**
   * The markup contents.
   *
   * @var string
   */
  protected $markup;

  /**
   * @return self
   */
  public static function create() {
    return new Markup();
  }

  /**
   * Returns this elements markup.
   *
   * @return mixed the markup.
   */
  public function getMarkup() {
    return $this->markup;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderMarkup( $this );
  }

  /**
   * Set the markup for this element.
   *
   * @param mixed $markup the markup
   * @return self this element so methods can be chained.
   */
  public function setMarkup( $markup ) {
    $this->markup = $markup;
    return $this;
  }


}
