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

use Substance\Core\Presentation\ElementBuilder;
use Substance\Core\Presentation\Theme;
use Substance\Core\Alert\Alert;

/**
 * A table cell, i.e. a container of elements.
 */
class TableCell extends Container {

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Container::build()
   */
  public static function build( $element ) {
    if ( is_array( $element ) ) {
      // The supplied element is an array, so we treat it as a build array.
      if ( !array_key_exists( '#type', $element ) ) {
        // The supplied element does not have a #type, so it's not a build array
        throw Alert::alert('TableCell build array requires #type property');
      } else if ( $element['#type'] != get_called_class() ) {
        // The supplied element has a #type, but it's not for a TableCell, so
        // we can't build it.
        throw Alert::alert('TableCell element can only build ' . __CLASS__ . ' elements')
          ->culprit( 'type', $element['#type'] );
      }
      // Check for the required #elements, as this contains the cell contents.
      if ( array_key_exists( '#elements', $element ) ) {
        return TableCell::create()->addElement(
          ElementBuilder::build( $element['#elements'] )
        );
      } else {
        throw Alert::alert('TableCell build array requires #element property');
      }
    } else {
      // The supplied element is not an array, so we treat it as cell contents,
      // using the standard element builder to handle it as markup.
      $element = array(
        '#type' => 'Substance\Core\Presentation\Elements\TableCell',
        '#elements' => $element,
      );
      return self::build( $element );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderTableCell( $this );
  }

}
