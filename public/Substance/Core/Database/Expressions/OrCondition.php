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
 * Represents a logical OR condition in a database query. One or more
 * conditions can be combined with the OR operator, simply add each condition
 * in sequence.
 *
 * SELECT * FROM table WHERE table.column1 OR table.column2
 */
class OrCondition implements Condition {

  /**
   * Adds the specified condition to be OR'd together with other Conditions in
   * this Conditition
   *
   * @param Condition $condition the condition to OR with other conditions
   * @return self
   */
  public function add( Condition $condition ) {
  }

}
