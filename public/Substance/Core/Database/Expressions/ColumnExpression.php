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

namespace Substance\Core\Database\Expressions;

use Substance\Core\Database\Database;

/**
 * A column expression for use in a SQL query.
 */
class ColumnExpression extends AbstractExpression {

  /**
   * @var string the column name.
   */
  protected $name;

  /**
   * @var string the table name.
   */
  protected $table;

  public function __construct( $name, $table = NULL ) {
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
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Database $database ) {
  	$string = '';
  	if ( isset( $this->table ) ) {
      $string .= $database->quoteTable( $this->table );
  	  $string .= '.';
  	}
    $string .= $database->quoteName( $this->name );
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
