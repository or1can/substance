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

namespace Substance\Core\Presentation;

use Substance\Core\Alert\Alert;

/**
 * The Element is the base of the rendering system. All output is handled by
 * building a structured Element, which is then rendered appropriately for the
 * current context.
 */
class ElementBuilder {

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
    if ( is_array( $element ) ) {
      if ( array_key_exists( '#type', $element ) ) {
        // We have an element array with a defined type, so we can just get on
        // with it.
        $class = $element['#type'];
        if ( is_subclass_of( $class, 'Substance\Core\Presentation\Element' ) ) {
          return $class::build( $element );
        } else {
          throw Alert::alert(
            'Can only build elements of type Element',
            'Supplied #type must be the class name of a class that extends Substance\Core\Presentation\Element'
          )->culprit( 'supplied class', $class );
        }
      } else {
        // We have an element array without a defined type, so we default the
        // type to markup and carry on as normal.
        $element['#type'] = 'Substance\Core\Presentation\Elements\Markup';
        return self::build( $element );
      }
    } else {
      // It's not an array, so treat it as markup and carry on as normal.
      $element = array(
        '#type' => 'Substance\Core\Presentation\Elements\Markup',
        '#markup' => $element,
      );
      return self::build( $element );
    }
  }

}
