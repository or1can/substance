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

namespace Substance\Core\Database\SQL\TableReferences;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Query;

/**
 * Represents a simple table name reference in a query, e.g.
 *
 * e.g. the
 *     table AS tab
 * part of:
 *     SELECT table.column1 AS col FROM table AS tab
 */
class TableName extends AbstractTableReference {

  /**
   * @var string the table alias.
   */
  protected $alias;

  /**
   * @var string the table name.
   */
  protected $table;

  /**
   * Constructs a new table name expression for the specified table name.
   *
   * @param string $table the table name
   * @param string $alias the table name alias
   */
  public function __construct( $table, $alias = NULL ) {
    $this->table = $table;
    $this->alias = $alias;
  }

  public function __toString() {
    $string = '';
    $string .= $this->table;
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = '';
    $string .= $database->quoteTable( $this->table );
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $database->quoteName( $this->alias );
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\TableReference::define()
   */
  public function define( Query $query ) {
    $query->defineTableName( $this );
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
    return $this->table;
  }

}