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

namespace Substance\Core\Database\SQL\Expressions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\QueryLocation;

/**
 * Represents the select list in a SELECT query.
 *
 * e.g. the
 *     column1, column2
 * part of
 *     SELECT column1, column2 FROM table
 */
class SelectListExpression extends AbstractExpression implements QueryLocation {

  /**
   * @var Expression select list expression.
   */
  protected $select_list = NULL;

  /**
   * Adds the expression to the select list.
   *
   * @param Expression $expression the expression to add to the select list.
   */
  public function add( Query $query, Expression $expression ) {
    // Let the expression handle any pre-conditions, etc.
    $expression->aboutToAddQuery( $query, $this );
    // Now add the expression to the select list.
    if ( is_null( $this->select_list ) ) {
      $this->select_list = $expression;
    } elseif ( $this->select_list instanceof CommaExpression ) {
      $this->select_list->addExpressionToSequence( $expression );
    } else {
      $this->select_list = new CommaExpression( $this->select_list, $expression );
    }
  }

  public function __toString() {
    $string = '';
    if ( is_null( $this->select_list ) ) {
      $string .= '/* No select expressions */';
    } else {
      $string .= (string) $this->select_list;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Database $database ) {
    $string = '';
    if ( is_null( $this->select_list ) ) {
      $string .= '/* No select expressions */';
    } else {
      $string .= $this->select_list->build( $database );
    }
    return $string;
  }

}
