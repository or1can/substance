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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Expression;

/**
 * Represents a NULL test condition in a database query.
 *
 * e.g.the
 *     table.column1 IS NULL
 * and
 *     table column2 IS NOT NULL
 * part of:
 *     SELECT * FROM table WHERE table.column1 IS NULL AND table.column2 IS NOT NULL
 */
class IsNullExpression extends AbstractPostfixExpression {

  protected $not;

  /**
   * Constructs a new null test expression.
   *
   * @param Expression $left the expression to perform a null test on.
   * @param boolean $not FALSE for an 'IS NULL' test or TRUE for an
   * 'IS NOT NULL' test.
   */
  public function __construct( Expression $left, $not = FALSE ) {
    parent::__construct( $left );
    if ( is_bool( $not ) ) {
      $this->not = $not;
    } else {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'not must be a boolean' )
        ->culprit( 'not', $not );
    }
  }

  /**
   * Returns a Conditition object representing an IS NOT NULL check on the
   * specified column.
   *
   * @param string $column the column to check for a not NULL value.
   * @return NullCondition a NullCondition for an IS NOT NULL check on the
   * specified column.
   */
  public static function isNotNull( $column ) {

  }

  /**
   * Returns a Conditition object representing an IS NULL check on the
   * specified column.
   *
   * @param string $column the column to check for a NULL value.
   * @return NullCondition a NullCondition for an IS NULL check on the
   * specified column.
   */
  public static function isNull( $column ) {

  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\PostfixExpression::getSymbol()
   */
  public function getSymbol() {
    return $this->not ? 'IS NOT NULL' : 'IS NULL';
  }

}
