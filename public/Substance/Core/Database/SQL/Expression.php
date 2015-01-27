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

namespace Substance\Core\Database\SQL;

use Substance\Core\Database\Database;

/**
 * Represents an expression in a SQL query.
 */
interface Expression {

  /**
   * Should be called before this expression is added to a query at the
   * specified location.
   *
   * @param Query $query the query this expression is about to be added to
   * @param QueryLocation $location the location within the query
   */
  public function aboutToAddQuery( Query $query, QueryLocation $location );

  /**
   * Builds this expression for the given database connection.
   *
   * @param Database $database the database connection to build the expression
   * for
   * @return string the built expression as a string.
   */
  public function build( Database $database );

}
