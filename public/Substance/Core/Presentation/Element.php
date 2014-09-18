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

use Substance\Core\Alert\Alert;

/**
 * The Element is the base of the rendering system. All output is handled by
 * building a structured Element, which is then rendered appropriately for the
 * current context.
 */
abstract class Element implements Renderable {

  /**
   * The elements attributes.
   *
   * @var ElementAttributes
   */
  protected $attributes;

  /**
   * Constructs a new Element.
   */
  public function __construct() {
     $this->attributes = new ElementAttributes();
  }

  /**
   * Returns a new instance of the element. This method should be overridden in
   * all subclasses.
   * @return self A new instance of the element.
   */
  public static function create() {
    return new static;
  }

  /**
   * Returns a new instance of the element, using the supplied object.
   * Generally, this object will be an array with key-value pairs describing
   * properties of the element to be constructed. Some elements may allow
   * objects other than arrays. This method should be overridden in all
   * subclasses wishing to support this feature.
   *
   * Keys
   *
   * @param $element Properties describing the element to be created.
   * @return self A new instance of the element.
   */
  public static function build( $element ) {
    $element_class = get_called_class();
    throw Alert::alert('Cannot build element', "$element_class does not support building instances from array.");
  }

  /**
   * Returns the elements attributes.
   *
   * @return ElementAttributes the elements attributes.
   */
  public function getAttributes() {
    return $this->attributes;
  }

  /**
   * Renders this objects attributes using the specified Theme.
   *
   * This allows an object to have some control over how its attributes are
   * themed.
   *
   * @param Theme $theme the Theme to render this objects attributes with.
   * @return string this objects attributes rendered in the specified Theme.
   */
  public function renderAttributes( Theme $theme ) {
    return $theme->renderElementAttributes( $this->attributes );
  }

}
