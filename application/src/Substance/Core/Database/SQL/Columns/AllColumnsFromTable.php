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

namespace Substance\Core\Database\SQL\Columns;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Column;

/**
 * An all columns from a table column for use in a SQL query.
 *
 * e.g. the
 *     table1.*
 * part of:
 *     SELECT table1.* FROM table1 INNER JOIN table2
 */
class AllColumnsFromTable implements Column {

  /**
   * @var string the table name or alias.
   */
  protected $table;

  /**
   * Constructs a new all columns from table column, to select all colums in a
   * specific table.
   *
   * @param string $table the table name or alias to select all columns from.
   */
  public function __construct( $table ) {
    $this->table = $table;
  }

  public function __toString() {
    $string = $this->table;
    $string .= '.*';
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Column::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query, QueryLocation $location ) {
    // Nothing to do.
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    // FIXME - This needs to support aliases and tables.
    $string = $database->quoteTable( $this->table );
    $string .= '.*';
    return $string;
  }

}
