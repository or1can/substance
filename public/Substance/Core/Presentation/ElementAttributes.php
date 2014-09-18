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

namespace Substance\Core\Presentation;

/**
 * The ElementAttributes class represents an elements attributes.
 */
class ElementAttributes implements Renderable {

  /**
   * An associative array of element name it's corresponding ElementAttribute
   * object.
   *
   * @var array
   */
  protected $attributes = array();

  /**
   * @param ElementAttribute $element_attribute
   */
  public function add( ElementAttribute $element_attribute ) {
    $this->attributes[ $element_attribute->getName() ] = $element_attribute;
  }

  /**
   * Returns the elements attributes.
   *
   * @return array
   */
  public function getAttributes() {
    return $this->attributes;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Renderable::render()
   */
  public function render( Theme $theme ) {
    $theme->renderElementAttributes( $this );
  }

}
