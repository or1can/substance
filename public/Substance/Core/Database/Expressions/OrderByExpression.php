<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2015 Kevin Rogers
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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Expression;

/**
 * Represents a column or expression in an ORDER BY section of a database
 * query.
 *
 * e.g. the
 *     ORDER BY table.column1 ASC
 * part of:
 *     SELECT * FROM table ORDER BY table.column1 ASC
 */
class OrderByExpression extends AbstractPostfixExpression {

  protected $direction;

  public function __construct( Expression $left, $direction ) {
    if ( $left instanceof OrderByExpression ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'Cannot ORDER BY an OrderByExpression' )
        ->culprit( 'left', $left );
    }
    parent::__construct( $left );
    if ( !in_array( $direction, array( 'ASC', 'DESC' ) ) ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'Only ASC or DESC are allowed in ORDER BY' )
        ->culprit( 'order', $direction );
    }
    $this->direction = $direction;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\PostfixExpression::getSymbol()
   */
  public function getSymbol() {
    return $this->direction;
  }

}
