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
 * A simple style element.
 */
abstract class Style extends Element {

  /**
   * The media type.
   *
   * @var string
   */
  protected $media_type = 'all';

  /**
   * Returns this elements media type.
   *
   * @return mixed the media type.
   */
  public function getMediaType() {
    return $this->media_type;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderStyle( $this );
  }

  /**
   * Set the media type for this element.
   *
   * @param mixed $media_type the media type
   * @return self this element so methods can be chained.
   */
  public function setMediaType( $media_type ) {
    $this->media_type = $media_type;
    return $this;
  }

}
