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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Database\Expression;

/**
 * Represents a comma in a database query, combining two expressions with a
 * comma. This is perhaps not strictly an expression, but it behaves in a near
 * identical fashion.
 *
 * SELECT a, b FROM table
 */
class CommaExpression extends AbstractInfixExpression {

  public function __construct( Expression $left, Expression $right ) {
    parent::__construct( $left, $right );
    $this->has_space_before = FALSE;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\InfixExpression::getSymbol()
   */
  public function getSymbol() {
    return ',';
  }

}
