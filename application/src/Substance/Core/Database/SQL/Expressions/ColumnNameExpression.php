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

namespace Substance\Core\Database\SQL\Expressions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * A column name expression for use in a SQL query.
 */
class ColumnNameExpression extends NameExpression {

  /**
   * @var TableName the table name.
   */
  protected $table_name;

  /**
   * Constructs a new column name expression for the specified column in the
   * optionally specified table.
   *
   * @param string $name the column name
   * @param TableName $table the table name.
   */
  public function __construct( $name, TableName $table_name = NULL ) {
    parent::__construct( $name );
    $this->table_name = $table_name;
  }

  public function __toString() {
    $string = '';
    if ( isset( $this->table_name ) ) {
      $string .= (string) $this->table_name;
      $string .= '.';
    }
    $string .= (string) parent::__toString();
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
  	$string = '';
  	if ( isset( $this->table_name ) ) {
      $string .= $this->table_name->buildReference( $database );
  	  $string .= '.';
  	}
    $string .= parent::build( $database );
  	return $string;
  }

}
