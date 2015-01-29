<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 - 2015 Kevin Rogers
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

namespace Substance\Core\Database\SQL\Components;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Column;
use Substance\Core\Database\SQL\Component;
use Substance\Core\Database\SQL\Query;

/**
 * Represents the select list in a SELECT query.
 *
 * e.g. the
 *     column1, column2
 * part of
 *     SELECT column1, column2 FROM table
 */
class SelectList implements Component {

  /**
   * @var array select list columns.
   */
  protected $columns = array();

  /**
   * Adds the column to the select list.
   *
   * @param Column $column the column to add to the select list.
   */
  public function add( Query $query, Column $column ) {
    // Let the expression handle any pre-conditions, etc.
    $column->aboutToAddQuery( $query, $this );
    // Now add the expression to the select list.
    $this->columns[] = $column;
  }

  public function __toString() {
    $string = '';
    if ( is_null( $this->columns ) ) {
      $string .= '/* No select expressions */';
    } else {
      $parts = array();
      foreach ( $this->columns as $column ) {
        $parts[] = (string) $column;
      }
      $string .= implode( ', ', $parts );
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = '';
    if ( is_null( $this->columns ) ) {
      $string .= '/* No select expressions */';
    } else {
      $parts = array();
      foreach ( $this->columns as $column ) {
        $parts[] = $column->build( $database );
      }
      $string .= implode( ', ', $parts );
    }
    return $string;
  }

}
