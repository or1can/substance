<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
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

use Substance\Core\Database\Connection;
use Substance\Core\Database\Expression;

/**
 * A column expression for use in a SQL query.
 */
class ColumnExpression implements Expression {

  /**
   * @var string the column alias.
   */
  protected $alias;

  /**
   * @var string the column name.
   */
  protected $name;

  /**
   * @var string the table name.
   */
  protected $table;

  public function __construct( $name, $table = NULL, $alias = NULL ) {
    $this->alias = $alias;
    $this->name = $name;
    $this->table = $table;
  }

  public function __toString() {
    $string = '';
    if ( isset( $this->table ) ) {
      $string .= $this->table;
      $string .= '.';
    }
    $string .= $this->name;
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Connection $connection ) {
  	$string = '';
  	if ( isset( $this->table ) ) {
      $string .= $connection->quoteTable( $this->table );
  	  $string .= '.';
  	}
    $string .= $connection->quoteName( $this->name );
  	if ( isset( $this->alias ) ) {
  	  $string .= ' AS ';
      $string .= $connection->quoteName( $this->alias );
  	  $string .= $this->alias;
  	}
  	return $string;
  }

  /**
   * Returns the field name.
   *
   * @return string the field name.
   */
  public function getName() {
    return $this->name;
  }

}
