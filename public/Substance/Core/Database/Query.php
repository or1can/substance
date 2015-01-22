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

namespace Substance\Core\Database;

use Substance\Core\Database\Queries\Select;
/**
 * Represents a database query.
 */
abstract class Query {

  /**
   * Builds this query for the specified database connection.
   *
   * @param Database $database the database connection to build the query
   * for
   * @return string the built query as a string.
   */
  abstract public function build( Database $database );

  /**
   * Creates a new SELECT query to select data from the specified table.
   *
   * @return \Substance\Core\Database\Queries\Select
   */
  public static function select( $table ) {
    return new Select( $table );
  }

}
