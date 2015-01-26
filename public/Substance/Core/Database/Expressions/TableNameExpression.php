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

use Substance\Core\Database\Database;
use Substance\Core\Database\Query;
use Substance\Core\Database\QueryLocation;

/**
 * A table name expression for use in a SQL query.
 *
 * e.g. the
 *     table AS tab
 * part of:
 *     SELECT table.column1 AS col FROM table AS tab
 */
class TableNameExpression extends AbstractExpression {

  /**
   * @var string the table alias.
   */
  protected $alias;

  /**
   * @var string the table name.
   */
  protected $name;

  /**
   * Constructs a new table name expression for the specified table name.
   *
   * @param string $name the table name
   * @param string $alias the table name alias
   */
  public function __construct( $name, $alias = NULL ) {
    $this->name = $name;
    $this->alias = $alias;
  }

  public function __toString() {
    $string = '';
    $string .= $this->name;
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Expression::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query, QueryLocation $location ) {
    // FIXME - This is wrong, as a table alias cannot be added to a select
    // list...
    if ( $location instanceof SelectListExpression ) {
      $query->defineTableAlias( $this );
    } else {
      throw Alert::alert( 'Invalid location for table alias', 'Table aliases can only be used in [FIXME]' )
        ->culprit( 'query location', $location )
        ->culprit( 'query', $query );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Database $database ) {
    $string = '';
    $string .= $database->quoteTable( $this->name );
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $database->quoteName( $this->alias );
    }
    return $string;
  }

  /**
   * Returns the table alias.
   *
   * @return string the table alias.
   */
  public function getAlias() {
    return $this->alias;
  }

  /**
   * Returns the table name.
   *
   * @return string the table name.
   */
  public function getName() {
    return $this->name;
  }

}
