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
 * The ElementAttribute is an Element that represents an attribute of another
 * Element. For example, a id attribute on a form element.
 */
class ElementAttribute implements Renderable {

  /**
   * The attribute name.
   *
   * @var string
   */
  protected $name;

  /**
   * The attribute value.
   *
   * @var srtring
   */
  protected $value;

  public function __construct( $name, $value ) {
    $this->name = $name;
    $this->value = $value;
  }

  /**
   * Returns this attributes name.
   *
   * @return string the attributes name.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Returns this attributes value.
   *
   * @return string the attributes value.
   */
  public function getValue() {
    return $this->value;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Renderable::render()
   */
  public function render( Theme $theme ) {
    $theme->renderElementAttribute( $this );
  }

  /**
   * Set the attributes name.
   *
   * @param string $name the attributes name
   * @return self this element so methods can be chained.
   */
  public function setName( $name ) {
    $this->name = $name;
    return $this;
  }

  /**
   * Set the attributes value.
   *
   * @param string $name the attributes value
   * @return self this element so methods can be chained.
   */
  public function setValue( $value ) {
    $this->value = $value;
    return $this;
  }

}
