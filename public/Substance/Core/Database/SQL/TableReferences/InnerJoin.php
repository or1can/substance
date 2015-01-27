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

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\QueryLocation;
use Substance\Core\Database\SQL\TableReference;

/**
 * Represents an inner join in a query, e.g.
 *
 * e.g. the
 *     table INNER JOIN table2 USING ( column1 )
 * part of:
 *     SELECT * FROM table INNER JOIN table2 USING ( column1 )
 */
class InnerJoin extends AbstractTableReference {

  /**
   * @var TableReference the left hand side table reference
   */
  protected $left;

  /**
   * @var TableReference the right hand side table reference
   */
  protected $right;

  /**
   * Construct a new inner join expression, to join the two specified table
   * references together.
   *
   * @param TableReference $left the left table reference
   * @param TableReference $right the right table reference
   */
  public function __construct( TableReference $left, TableReference $right ) {
    $this->left = $left;
    $this->right = $right;
  }

  public function __toString() {
    $string = '';
    $string .= $this->left;
    $string .= ' INNER JOIN ';
    $string .= $this->right;
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = '';
    $string .= $this->left->build( $database );
    $string .= ' INNER JOIN ';
    $string .= $this->right->build( $database );
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\TableReference::define()
   */
  public function define( Query $query ) {
    // We must define the table reference on the left and on the right.
    $this->left->define( $query );
    $this->right->define( $query );
  }

  /**
   * Returns the left hand side table reference.
   *
   * @return \Substance\Core\Database\SQL\TableReference
   */
  public function getLeftTableReference() {
    return $this->left;
  }

  /**
   * Returns the right hand side table reference.
   *
   * @return \Substance\Core\Database\SQL\TableReference
   */
  public function getRightTableReference() {
    return $this->right;
  }

}
