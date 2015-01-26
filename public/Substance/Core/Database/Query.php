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

namespace Substance\Core\Database;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Database\Expressions\AbstractAliasExpression;
use Substance\Core\Database\Expressions\ColumnAliasExpression;
use Substance\Core\Database\Expressions\TableAliasExpression;

/**
 * Represents a database query.
 */
abstract class Query {

  protected $alias_column = array();

  protected $alias_table = array();

  /**
   * Builds this query for the specified database connection.
   *
   * @param Database $database the database connection to build the query
   * for
   * @return string the built query as a string.
   */
  abstract public function build( Database $database );

  /**
   * Defines the specifed column alias for this query.
   *
   * @param ColumnAliasExpression $expression the new column alias.
   */
  public function defineColumnAlias( ColumnAliasExpression $expression ) {
    if ( $this->hasColumnAlias( $expression->getAlias() ) ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Duplicate column alias', 'You can only define a column alias once in a query' )
        ->culprit( 'column alias', $expression->getAlias() );
    } else {
      $this->alias_column[ $expression->getAlias() ] = $expression;
    }
  }

  /**
   * Defines the specifed table alias for this query.
   *
   * @param TableAliasExpression $expression the new table alias.
   */
  public function defineTableAlias( TableAliasExpression $expression ) {
    if ( $this->hasTableAlias( $expression->getAlias() ) ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Duplicate table alias', 'You can only define a table alias once in a query' )
        ->culprit( 'table alias', $expression->getAlias() );
    } else {
      $this->alias_table[ $expression->getAlias() ] = $expression;
    }
  }

  /**
   * Checks if the specified column alias has been defined for this query.
   *
   * @param string $alias the column alias to check.
   * @return boolean TRUE if the alias already exists and FALSE otherwise.
   */
  public function hasColumnAlias( $alias ) {
    return array_key_exists( $alias, $this->alias_column );
  }

  /**
   * Checks if the specified table alias has been defined for this query.
   *
   * @param string $alias the table alias to check.
   * @return boolean TRUE if the alias already exists and FALSE otherwise.
   */
  public function hasTableAlias( $alias ) {
    return array_key_exists( $alias, $this->alias_table );
  }

  /**
   * Creates a new SELECT query to select data from the specified table.
   *
   * @return \Substance\Core\Database\Queries\Select
   */
  public static function select( $table ) {
    return new Select( $table );
  }

}
