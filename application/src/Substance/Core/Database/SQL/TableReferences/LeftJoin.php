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
use Substance\Core\Database\SQL\TableReference;

/**
 * Represents a left join in a query, e.g.
 *
 * e.g. the
 *     table LEFT JOIN table2 USING ( column1 )
 * part of:
 *     SELECT * FROM table LEFT JOIN table2 USING ( column1 )
 */
class LeftJoin extends AbstractTableReference {

  /**
   * @var JoinCondition the join condition.
   */
  protected $condition;

  /**
   * @var TableReference the left hand side table reference
   */
  protected $left;

  /**
   * @var TableReference the right hand side table reference
   */
  protected $right;

  /**
   * Construct a new left join expression, to join the two specified table
   * references together.
   *
   * @param TableReference $left the left table reference
   * @param TableReference $right the right table reference
   */
  public function __construct( TableReference $left, TableReference $right, JoinCondition $condition = NULL ) {
    $this->left = $left;
    $this->right = $right;
    $this->condition = $condition;
  }

  public function __toString() {
    $string = '';
    $string .= (string) $this->left;
    $string .= ' LEFT JOIN ';
    $string .= (string) $this->right;
    if ( isset( $this->condition ) ) {
      $string .= ' ';
      $string .= (string) $this->condition;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = '';
    $string .= $this->left->build( $database );
    $string .= ' LEFT JOIN ';
    $string .= $this->right->build( $database );
    if ( isset( $this->condition ) ) {
      $string .= ' ';
      $string .= $this->condition->build( $database );
    }
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
