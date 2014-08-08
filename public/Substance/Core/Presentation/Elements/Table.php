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
 * A table, i.e. a container of TableRows.
 */
class Table extends Container {

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Elements\Container::addElement()
   */
  public function addElement( Element $element ) {
    for ( $i = 0; $i < func_num_args(); $i++ ) {
      $elem = func_get_arg( $i );
      if ( $elem instanceof TableRow ) {
        parent::addElement( $elem );
      } else {
        throw Alert::alert( 'Table row required', 'Only TableRow\'s can be added to a table' )
          ->culprit( 'element', $element );
      }
    }
    return $this;
  }

  /**
   * Add's a row to this table.
   *
   * This method is a shorthand for addElement, to make code a little clearer.
   *
   * @param TableRow ...$row the row to add
   */
  public function addRow( TableRow $row ) {
    call_user_func_array( array( $this, 'addElement' ), func_get_args() );
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderTable( $this );
  }

}
