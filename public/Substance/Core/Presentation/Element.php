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
abstract class Element {

  /**
   * The elements unique identifier.
   *
   * @var string
   */
  protected $id;

  /**
   * Constructs a new Element.
   */
  public function __construct() {
     $this->id = ElementId::newElementId( get_called_class() );
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
   * Returns the unique identifier for this element.
   *
   * @return ElementId the unique identifier for this element.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Renders this element using the specific Theme.
   *
   * This allows an Element to have some control over how it is themed. For
   * most Elements, this is implemented as a simple callback to a specific
   * rendering method in the specified Theme - but this may not be appropriate
   * for all Elements.
   *
   * @param Theme $theme
   */
  abstract public function render( Theme $theme );

}
