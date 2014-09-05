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
use Substance\Core\Presentation\ElementBuilder;
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
   * @param TableCell ...$cell the cell to add
   */
  public function addCell( TableCell $cell ) {
    call_user_func_array( array( $this, 'addElement' ), func_get_args() );
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Elements\Container::addElement()
   */
  public function addElement( Element $element ) {
    for ( $i = 0; $i < func_num_args(); $i++ ) {
      $elem = func_get_arg( $i );
      if ( $elem instanceof TableCell ) {
        parent::addElement( $elem );
      } else {
        throw Alert::alert( 'Table cell required', 'Only TableCell\'s can be added to a table row' )
          ->culprit( 'element', $element );
      }
    }
    return $this;
  }

  /**
   * $elements - a build array or array of simple cells.
   * $elements['#row'] - the table row, either an array of simple cells or an
   * array of table cell build arrays.
   *
   * @see \Substance\Core\Presentation\Container::build()
   */
  public static function build( $element ) {
    if ( is_array( $element ) ) {
      // Check if $element has a '#type' key, in which case, treat $element as
      // a full build array.
      if ( array_key_exists( '#type', $element ) ) {
        if ( $element['#type'] != get_called_class() ) {
          // The supplied element has a #type, but it's not for a TableCell, so
          // we can't build it.
          throw Alert::alert('TableRow element can only build ' . __CLASS__ . ' elements')
            ->culprit( 'type', $element['#type'] );
        }
        // Check for the required #row, as this contains the row contents.
        if ( array_key_exists( '#row', $element ) ) {
          if ( is_array( $element['#row'] ) ) {
            // Iterate over the row to build each cell.
            $table_row = TableRow::create();
            foreach ( $element['#row'] as $cell ) {
              if ( is_array( $cell ) ) {
                // The row is an array, so handle like an other build array.
                $table_row->addCell( ElementBuilder::build( $cell ) );
              } else {
                // The row is not an array, so we treat this as markup and have
                // to deliberately wrap it in a table cell.
                $table_row->addCell(
                    TableCell::createWithElement( ElementBuilder::build( $cell ) )
                );
              }
            }
            return $table_row;
          } else {
            throw Alert::alert('TableRow build array #row property must be an array');
          }
        } else {
          throw Alert::alert('TableRow build array requires #row property');
        }
      } else {
        // The supplied build array doesn't have a type, so it must be an array
        // of simple cells, so rebuild it as a proper build array and build.
        $element = array(
          '#type' => 'Substance\Core\Presentation\Elements\TableRow',
          '#row' => $element,
        );
        return self::build( $element );
      }
    } else {
      throw Alert::alert(
        'TableRow can only be built with an array',
        'Building a TableRow requires either an array of simple cells (i.e. strings) or an array of build arrays (i.e. TableCells).'
      )->culprit( 'element', $element );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderTableRow( $this );
  }

}
