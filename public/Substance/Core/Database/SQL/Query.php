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

namespace Substance\Core\Database\SQL;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expressions\ColumnAliasExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Represents a database query.
 */
abstract class Query {

  protected $aliases_column = array();

  protected $aliases_table = array();

  protected $tables = array();

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
      $this->aliases_column[ $expression->getAlias() ] = $expression;
    }
  }

  /**
   * Defines the specifed table name for this query.
   *
   * @param TableNameExpression $expression the new table name.
   */
  public function defineTableName( TableName $table_name ) {
    if ( is_null( $table_name->getAlias() ) ) {
      // Table has no alias, so check we don't have the table name twice.
      if ( $this->hasTable( $table_name->getName() ) ) {
        // TODO - Would an Illegal argument alert be useful?
        throw Alert::alert( 'Duplicate table', 'You can only define a table once in a query' )
          ->culprit( 'table name', $table_name->getName() );
      } else {
        $this->tables[ $table_name->getName() ] = $table_name;
      }
    } else {
      // Table has an alias, so check we don't have the alias twice.
      if ( $this->hasTableAlias( $table_name->getAlias() ) ) {
        // TODO - Would an Illegal argument alert be useful?
        throw Alert::alert( 'Duplicate table alias', 'You can only define a table alias once in a query' )
          ->culprit( 'table alias', $table_name->getAlias() );
      } else {
        $this->aliases_table[ $table_name->getAlias() ] = $table_name;
      }
    }
  }

  /**
   * Checks if the specified column alias has been defined for this query.
   *
   * @param string $alias the column alias to check.
   * @return boolean TRUE if the alias already exists and FALSE otherwise.
   */
  public function hasColumnAlias( $alias ) {
    return array_key_exists( $alias, $this->aliases_column );
  }

  /**
   * Checks if the specified table alias has been defined for this query.
   *
   * @param string $alias the table alias to check.
   * @return boolean TRUE if the alias already exists and FALSE otherwise.
   */
  public function hasTableAlias( $alias ) {
    return array_key_exists( $alias, $this->aliases_table );
  }

  /**
   * Checks if the specified table has been defined for this query.
   *
   * @param string $alias the table to check.
   * @return boolean TRUE if the table already exists and FALSE otherwise.
   */
  public function hasTable( $table ) {
    return array_key_exists( $table, $this->tables );
  }

  /**
   * Creates a new SELECT query to select data from the specified table.
   *
   * @param string $table the table to select data from
   * @param string $alias the alias for the table being selected from
   * @return \Substance\Core\Database\SQL\Queries\Select
   */
  public static function select( $table, $alias = NULL ) {
    return new Select( new TableName( $table, $alias ) );
  }

}
