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

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\Query;

/**
 * Represents a simple table name reference in a query, e.g.
 *
 * e.g. the
 *     table AS tab
 * part of:
 *     SELECT table.column1 AS col FROM table AS tab
 *
 * A table alias can either mutable or immutable, i.e. the alias you specify
 * must be used as-is, or the system can generate a unique alias if there is a
 * clash.
 *
 */
class TableName extends AbstractTableReference {

  /**
   * @var string the table alias.
   */
  protected $alias;

  /**
   * @var boolean whether the alias is mutable or not.
   */
  protected $alias_mutable = FALSE;

  /**
   * @var string the table name.
   */
  protected $table;

  /**
   * Constructs a new table name expression for the specified table name.
   *
   * @param string $table the table name
   * @param string $alias the table name alias
   * @param boolean $alias_mutable TRUE if the alias is mutable and FALSE
   * otherwise.
   */
  public function __construct( $table, $alias = NULL, $alias_mutable = FALSE ) {
    $this->table = $table;
    if ( isset( $alias ) ) {
      $this->alias = $alias;
    } else {
      $this->alias = $this->table;
    }
    $this->alias_mutable = $alias_mutable;
  }

  public function __toString() {
    $string = '';
    $string .= $this->table;
    if ( $this->alias !== $this->table ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    return $database->buildTableName( $this );
  }

  /**
   * Builds this table name for inclusion in a query as a reference to this
   * table. This will be the table name if it has no alias, otherwise the alias.
   *
   * e.g. the
   *     `t`
   * part of
   *     SELECT `t`.* FROM `table` AS `t`
   *
   * or the
   *     `db`.`table`
   * part of
   *     SELECT `db`.`table`.* FROM `db`.`table`
   *
   * @param Database $database the database to build the component for
   * @return string the built component as a string.
   */
  public function buildReference( Database $database ) {
    return $database->quoteTable( $this->alias );
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

  /**
   * Checks if the alias is mutable.
   *
   * @return boolean TRUE if the alias is mutable and FALSE otherwise.
   */
  public function isAliasMutable() {
    return $this->alias_mutable;
  }

  /**
   * Sets the alias for this table name.
   *
   * @param string $alias the alias for this table name.
   */
  public function setAlias( $alias ) {
    $this->alias = $alias;
  }

}
