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

namespace Substance\Core\Database\SQL\Columns;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Column;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Query;

/**
 * Represents a column alias in a query, e.g.
 *
 * e.g. the
 *     table.column1 AS col
 * part of:
 *     SELECT table.column1 AS col FROM table AS tab
 */
class ColumnWithAlias implements Column {

  /**
   * @var string the table alias.
   */
  protected $alias;

  /**
   * @var Expression the left expression.
   */
  protected $left;

  /**
   * Constructs a new table name expression for the specified table name.
   *
   * @param string $table the table name
   * @param string $alias the table name alias
   */
  public function __construct( Expression $left, $alias ) {
    $this->left = $left;
    $this->alias = $alias;
  }

  public function __toString() {
    $string = (string) $this->left;
    $string .= ' AS ';
    $string .= $this->alias;
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Column::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query ) {
    // Nothing to do.
    $query->defineColumnAlias( $this );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = $this->left->build( $database );
    $string .= ' AS ';
    $string .= $database->quoteName( $this->alias );
    return $string;
  }

  /**
   * Returns the alias name.
   *
   * @return string the alias name.
   */
  public function getAlias() {
    return $this->alias;
  }

}
