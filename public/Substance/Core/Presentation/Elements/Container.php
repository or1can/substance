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
 * A container for other Elements.
 */
class Container extends Element {

  /**
   * The containers elements.
   *
   * @var Element[]
   */
  protected $elements = array();

  /**
   * Adds an Element to the container.
   *
   * @param Element ...$element the Element to add.
   * @return self this element so methods can be chained.
   */
  public function addElement( Element $element ) {
    for ( $i = 0; $i < func_num_args(); $i++ ) {
      $elem = func_get_arg( $i );
      if ( $elem instanceof Element ) {
        $this->elements[] = $element;
      } else {
        throw new \InvalidArgumentException('Can only add Elements to a Container');
      }
    }
    return $this;
  }

  /**
   * Adds an array of Elements to the container.
   *
   * @param Element[] $elements the array of Elements to add.
   * @return self this element so methods can be chained.
   */
  public function addElements( array $elements ) {
    call_user_func_array( array( $this, 'addElement' ), $elements );
    return $this;
  }

  /**
   * Returns a new instance of this element, with the supplied element added to it.
   *
   * @param Element ...$element the Element to add.
   * @return self A new instance of this element.
   */
  public static function createWithElement( Element $element ) {
    $container = new static;
    $container->addElements( func_get_args() );
    return $container;
  }

  /**
   * Returns the containers elements.
   *
   * @return Element[] the containers elements.
   */
  public function getElements() {
    return $this->elements;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderContainer( $this );
  }

}
