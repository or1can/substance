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

use Substance\Core\Presentation\Theme;

/**
 * A simple inline style element.
 */
class InlineStyle extends Style {

  /**
   * The markup contents.
   *
   * @var string
   */
  protected $data;

  /**
   * Returns this elements data.
   *
   * @return string the data.
   */
  public function getData() {
    return $this->data;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderInlineStyle( $this );
  }

  /**
   * Set the data for this element.
   *
   * @param string $data the data
   * @return self this element so methods can be chained.
   */
  public function setData( $data ) {
    $this->data = $data;
    return $this;
  }

}
