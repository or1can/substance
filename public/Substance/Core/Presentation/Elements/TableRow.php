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

use Substance\Core\Alert\Alert;
use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\Theme;

/**
 * A table row, i.e a container of cell elements.
 */
class TableRow extends Container {

  /**
   * Add's a cell to this row.
   *
   * This method is a shorthand for addElement, to make code a little clearer.
   *
   * @param TableCell $cell the cell to add
   */
  public function addCell( TableCell $cell ) {
    return $this->addElement( $cell );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Elements\Container::addElement()
   */
  public function addElement( Element $element ) {
    if ( !( $element instanceof TableCell ) ) {
      throw Alert::alert( 'Table cell required', 'Only TableCell\'s can be added to a table row' )
        ->culprit( 'element', $element );
    }
    return parent::addElement( $element );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderTableRow( $this );
  }

}
