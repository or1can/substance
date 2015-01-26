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
 * Represents an alias in a query, e.g.
 *
 * e.g. the
 *     table.column1 AS col
 * part of:
 *     SELECT table.column1 AS col FROM table
 */
abstract class AbstractAliasExpression extends AbstractInfixExpression {

  public function __construct( Expression $left, $alias ) {
    // FIXME - We need to distinguish between chainable and non-chainable infix
    // expressions, as an alias expression cannot be chained, but an AND or OR
    // expression could be.
    if ( $left instanceof AliasExpression ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'Cannot alias an AliasExpression' )
        ->culprit( 'left', $left );
    }
    parent::__construct( $left, new NameExpression( $alias ) );
  }

  /**
   * Returns the alias name.
   *
   * @return string the alias name.
   */
  public function getAlias() {
    return $this->right->getName();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\PostfixExpression::getSymbol()
   */
  public function getSymbol() {
    return 'AS';
  }

}
