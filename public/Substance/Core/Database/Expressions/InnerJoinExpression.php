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
use Substance\Core\Database\Query;
use Substance\Core\Database\QueryLocation;
use Substance\Core\Database\TableReference;

/**
 * Represents an inner join in a query, e.g.
 *
 * e.g. the
 *     table INNER JOIN table2 USING ( column1 )
 * part of:
 *     SELECT * FROM table INNER JOIN table2 USING ( column1 )
 */
class InnerJoinExpression extends AbstractInfixExpression implements TableReference {

  /**
   * Construct a new inner join expression, to join the two specified tables
   * together.
   *
   * @param TableNameExpression $left the left table
   * @param TableNameExpression $right the right table
   */
  public function __construct( TableNameExpression $left, TableNameExpression $right ) {
    parent::__construct( $left, $right );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Expression::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query, QueryLocation $location ) {
    if ( $location instanceof SelectListExpression ) {
      $query->defineColumnAlias( $this );
    } else {
      throw Alert::alert( 'Invalid location for column alias', 'Column aliases can only be used in select lists' )
        ->culprit( 'query location', $location )
        ->culprit( 'query', $query );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\InfixExpression::getSymbol()
   */
  public function getSymbol() {
    return 'INNER JOIN';
  }

}
