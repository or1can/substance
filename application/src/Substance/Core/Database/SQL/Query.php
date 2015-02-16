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
use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\TableReferences\TableName;
use Substance\Core\Database\Drivers\Unconnected\UnconnectedDatabase;

/**
 * Represents a database query.
 */
abstract class Query {

  protected $aliases_column = array();

  protected $aliases_table = array();

  /**
   * @var array the array of argument placeholders to values.
   */
  protected $arguments = array();

  public function __toString() {
    $unconnected = UnconnectedDatabase::getInstance();
    return $this->build( $unconnected );
  }

  /**
   * Builds this query for the specified database connection.
   *
   * @param Database $database the database connection to build the query
   * for
   * @return string the built query as a string.
   */
  abstract public function build( Database $database );

  /**
   * Defines the specifed argument for this query, returning the unique alias
   * assigned to it.
   *
   * @param LiteralExpression $argument the argument value.
   * @return string the unique placeholder used for this argument.
   */
  public function defineArgument( LiteralExpression $argument ) {
    // Get a unique placeholder
    $unique_placeholder = $this->uniquePlaceholder(':dbph');
    // Check if the alias already exists, ignoring reserved aliases as we might
    // actually be defining them...
    if ( $this->hasArgument( $unique_placeholder, TRUE ) ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Duplicate argument placeholder', 'You can only define an argument placeholder once in a query' )
        ->culprit( 'placeholder', $unique_placeholder );
    } else {
      $this->arguments[ $unique_placeholder ] = $argument->getValue();
    }
    return $unique_placeholder;
  }

  /**
   * Defines the specifed column alias for this query.
   *
   * @param ColumnWithAlias $column the new column alias.
   */
  public function defineColumnAlias( ColumnWithAlias $column ) {
    // Check if the alias already exists, ignoring reserved aliases as we might
    // actually be defining them...
    if ( $this->hasColumnAlias( $column->getAlias(), TRUE ) ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Duplicate column alias', 'You can only define a column alias once in a query' )
        ->culprit( 'column alias', $column->getAlias() );
    } else {
      $this->aliases_column[ $column->getAlias() ] = $column;
    }
  }

  /**
   * Defines the specifed table name for this query.
   *
   * @param TableNameExpression $expression the new table name.
   */
  public function defineTableName( TableName $table_name ) {
    // Do not redefine table names.
    if ( array_search( $table_name, $this->aliases_table, TRUE ) === FALSE ) {
      // If the alias is mutable, ensure it is unique before defining it.
      if ( $table_name->isAliasMutable() ) {
        $table_name->setAlias(
            $this->uniqueTableAlias( $table_name->getAlias() )
        );
      }
      if ( $this->hasTableAlias( $table_name->getAlias(), TRUE ) ) {
        // TODO - Would an Illegal argument alert be useful?
        throw Alert::alert( 'Duplicate table', 'You can only define a table once in a query' )
        ->culprit( 'table', $table_name );
      } else {
        $this->aliases_table[ $table_name->getAlias() ] = $table_name;
      }
    }
  }

  /**
   * Returns the queries arguments.
   *
   * @return array the array of argument placeholders to values.
   */
  public function getArguments() {
    return $this->arguments;
  }

  /**
   * Gets the defined table name for the specified alias.
   *
   * @param string $alias the table alias
   * @return TableName the table name for the specified alias or NULL if the
   * alias has not been defined yet.
   */
  public function getTableName( $alias ) {
    if ( array_key_exists( $alias, $this->aliases_table ) ) {
      return $this->aliases_table[ $alias ];
    } else {
      return NULL;
    }
  }

  /**
   * Checks if the specified column alias has been defined (or reserved) for
   * this query.
   *
   * @param string $alias the column alias to check.
   * @param boolean $ignore_reserved TRUE to ignore reserved aliases when
   * checking if the column alias already exists.
   * @return boolean TRUE if the alias already exists or has been reserved, and
   * FALSE otherwise.
   */
  public function hasArgument( $argument, $ignore_reserved = FALSE ) {
    if ( $ignore_reserved ) {
      return isset( $this->arguments[ $argument ] );
    } else {
      return array_key_exists( $argument, $this->arguments );
    }
  }

  /**
   * Checks if the specified column alias has been defined (or reserved) for
   * this query.
   *
   * @param string $alias the column alias to check.
   * @param boolean $ignore_reserved TRUE to ignore reserved aliases when
   * checking if the column alias already exists.
   * @return boolean TRUE if the alias already exists or has been reserved, and
   * FALSE otherwise.
   */
  public function hasColumnAlias( $alias, $ignore_reserved = FALSE ) {
    if ( $ignore_reserved ) {
      return isset( $this->aliases_column[ $alias ] );
    } else {
      return array_key_exists( $alias, $this->aliases_column );
    }
  }

  /**
   * Checks if the specified table alias has been defined (or reserved) for
   * this query.
   *
   * @param string $alias the table alias to check.
   * @param boolean $ignore_reserved TRUE to ignore reserved aliases when
   * checking if the table alias already exists.
   * @return boolean TRUE if the alias already exists and FALSE otherwise.
   */
  public function hasTableAlias( $alias, $ignore_reserved = FALSE ) {
    if ( $ignore_reserved ) {
      return isset( $this->aliases_table[ $alias ] );
    } else {
    return array_key_exists( $alias, $this->aliases_table );
    }
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

  /**
   * Return a unique column alias using the specified alias as a base.
   *
   * If the specified alias does not already exist, it will be returned as is.
   * However, if the specified alias is already in use, a unique suffix will be
   * appended to generate a unique alias.
   *
   * For example, trying to reserve the already defined column alias "col" might
   * acutally reserve "col2" or even "col3" if "col2" is already defined.
   *
   * This allows you to suggest an alias in a query, but not have fatal errors
   * if it already exists, e.g.
   *
   *     $query->addExpression(
   *         new ColumnNameExpression('col'),
   *         $myalias = $query->uniqueColumnAlias('col')
   *     );
   *
   * and now $myalias will contain the unique alias for use elsewhere in the
   * query.
   *
   * @param string $alias the alias to reserve.
   * @return string the reserved unique alias.
   */
  public function uniqueColumnAlias( $alias ) {
    $unique_alias = $alias;
    if ( $this->hasColumnAlias( $unique_alias ) ) {
      // Generate unique suffix.
      // NOTE - If the alias "col" already exists, we'll skip "col1" and start
      // with "col2" - if we knew there would be more than one "col" alias,
      // "col" would have been "col1"...
      $suffix = 2;
      do {
        $unique_alias = $alias . $suffix++;
      } while ( $this->hasColumnAlias( $unique_alias ) );
    }
    // Now reserve the alias.
    $this->aliases_column[ $unique_alias ] = NULL;
    return $unique_alias;
  }

  /**
   * Return a unique placeholder using the specified placeholder as a base.
   *
   * If the specified placeholder does not already exist, it will be returned as
   * is. However, if the specified placeholder is already in use, a unique
   * suffix will be appended to generate a unique placeholder.
   *
   * For example, trying to reserve the already defined placeholder ":ph" might
   * acutally reserve ":ph_2" or even ":ph_3" if ":ph_2" is already defined.
   *
   * @param string $placeholder the placeholder to reserve.
   * @return string the reserved unique alias.
   */
  public function uniquePlaceholder( $argument ) {
    $unique_argument = $argument;
    if ( $this->hasArgument( $unique_argument ) ) {
      // Generate unique suffix.
      // NOTE - If the alias ":dbph" already exists, we'll skip ":dbph_1" and
      // start with ":dbph_2" - if we knew there would be more than one ":dbph"
      // alias, ":dbph" would have been ":dbph1"...
      $suffix = 2;
      do {
        $unique_argument = $argument . '_' . $suffix++;
      } while ( $this->hasArgument( $unique_argument ) );
    }
    // Now reserve the alias.
    $this->arguments[ $unique_argument ] = NULL;
    return $unique_argument;
  }

  /**
   * Return a unique column alias using the specified alias as a base.
   *
   * If the specified alias does not already exist, it will be returned as is.
   * However, if the specified alias is already in use, a unique suffix will be
   * appended to generate a unique alias.
   *
   * For example, trying to reserve the already defined column alias "col" might
   * acutally reserve "col2" or even "col3" if "col2" is already defined.
   *
   * This allows you to suggest an alias in a query, but not have fatal errors
   * if it already exists, e.g.
   *
   *     $table = new TableName( 'table', $query->uniqueTableAlias('t') );
   *     $query->addExpression( new ColumnNameExpression( 'col', $table ) );
   *
   * or
   *
   *     $query = Select::select( 'table', 't' );
   *     $query->innerJoinByName(
   *         'other_table',
   *         $other_table = $query->uniqueTableAlias('t')
   *     );
   *     $query->addExpression( new ColumnNameExpression( 'col', 't' ) );
   *     $query->addExpression( new ColumnNameExpression( 'col', $other_table ) );
   *
   * and now $myalias will contain the unique alias for use elsewhere in the
   * query.
   *
   * @param string $alias the alias to reserve.
   * @return string the reserved unique alias.
   */
  public function uniqueTableAlias( $alias ) {
    $unique_alias = $alias;
    if ( $this->hasTableAlias( $unique_alias ) ) {
      // Generate unique suffix.
      // NOTE - If the alias "col" already exists, we'll skip "col1" and start
      // with "col2" - if we knew there would be more than one "col" alias,
      // "col" would have been "col1"...
      $suffix = 2;
      do {
        $unique_alias = $alias . $suffix++;
      } while ( $this->hasTableAlias( $unique_alias ) );
    }
    // Now reserve the alias.
    $this->aliases_table[ $unique_alias ] = NULL;
    return $unique_alias;
  }

}
