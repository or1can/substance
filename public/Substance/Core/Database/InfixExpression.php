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

namespace Substance\Core\Database;

/**
 * Represents an infix expression.
 */
interface InfixExpression extends Expression {

  /**
   * Returns the expression on the left of the infix operator.
   *
   * @return Expression the left hand side expression.
   */
  public function getLeftExpression();

  /**
   * Returns the expression on the right of the infix operator.
   *
   * @return Expression the right hand side expression.
   */
  public function getRightExpression();

  /**
   * Returns the symbol for this expression.
   *
   * @return string this expressions symbol.
   */
  public function getSymbol();

}
