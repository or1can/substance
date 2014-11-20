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

namespace Substance\Core\Database\SQL;

use Substance\Core\Database\Connection;

/**
 * Represents a select expression in a SQL query.
 */
interface Expression {

  /**
   * Builds this expression for the given database connection.
   *
   * @param Connection $connection the database connection to build the
   * expression for
   * @return string the built expression as a string.
   */
  public function build( Connection $connection );

  /**
   * Returns the field name.
   *
   * @return string the field name.
   */
  public function getName();

  /**
   * Returns the field name quoted according to the database in use at the time
   * this method is called.
   *
   * @return string the quoted field name.
   */
  public function getQuotedName();

}