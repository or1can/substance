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

/**
 * A table name expression for use in a SQL query.
 */
class TableNameExpression extends NameExpression {

  /**
   * Constructs a new table name expression for the specified table name.
   *
   * @param string $table_name the table name
   */
  public function __construct( $table_name ) {
    parent::__construct( $table_name );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Database $database ) {
    return $database->quoteTable( $this->name );
  }

}
