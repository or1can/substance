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

use Substance\Core\Database\Connection;
use Substance\Core\Database\Expression;
use Substance\Core\Database\InfixExpression;

/**
 * A equals expression, representing an equality test of a left and right
 * expression.
 */
abstract class AbstractInfixExpression implements InfixExpression {

  /**
   * @var Expression the left expression.
   */
  protected $left;

  /**
   * @var Expression the right expression.
   */
  protected $right;

  public function __construct( Expression $left, Expression $right ) {
    $this->left = $left;
    $this->right = $right;
  }

  public function __toString() {
    $string = '';
    $string .= $this->left;
    $string .= ' ';
    $string .= $this->getSymbol();
    $string .= ' ';
    $string .= $this->right;
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Expression::build()
   */
  public function build( Connection $connection ) {
    $string = '';
    $string .= $this->left->build( $connection );
    $string .= ' ';
    $string .= $this->getSymbol();
    $string .= ' ';
    $string .= $this->right->build( $connection );
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\InfixExpression::getLeftExpression()
   */
  public function getLeftExpression() {
    return $this->left;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\InfixExpression::getRightExpression()
   */
  public function getRightExpression() {
    return $this->right;
  }

}
