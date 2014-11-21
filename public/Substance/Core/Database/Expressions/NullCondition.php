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

use Substance\Core\Condition;

/**
 * Represents a NULL test condition in a database query.
 *
 * SELECT * FROM table WHERE table.column IS NULL
 * SELECT * FROM table WHERE table.column IS NOT NULL
 */
class NullCondition implements Condition {

  /**
   * Returns a Conditition object representing an IS NOT NULL check on the
   * specified column.
   *
   * @param string $column the column to check for a not NULL value.
   * @return NullCondition a NullCondition for an IS NOT NULL check on the
   * specified column.
   */
  public static function isNotNull( $column ) {

  }

  /**
   * Returns a Conditition object representing an IS NULL check on the
   * specified column.
   *
   * @param string $column the column to check for a NULL value.
   * @return NullCondition a NullCondition for an IS NULL check on the
   * specified column.
   */
  public static function isNull( $column ) {

  }

}
