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

namespace Substance\Core\Database\SQL\Expressions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\InfixExpression;
use Substance\Core\Database\SQL\Query;

/**
 * A equals expression, representing an equality test of a left and right
 * expression.
 */
abstract class AbstractInfixExpression extends AbstractExpression implements InfixExpression {

  /**
   * @var boolean TRUE if a space should appear before the symbol and FALSE
   * otherwise.
   */
  protected $has_space_before = TRUE;

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
    if ( $this->has_space_before ) {
      $string .= ' ';
    }
    $string .= $this->getSymbol();
    $string .= ' ';
    $string .= $this->right;
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query ) {
    $this->left->aboutToAddQuery( $query );
    $this->right->aboutToAddQuery( $query );
  }

  /**
   * Adds the specified element to this infix expression. The right hand
   * expression is replaced with a new instance of this infix expression made
   * with the existing right hand expression and the suppled one.
   *
   * e.g. adding z to the expression x AND y would result in x AND y AND z.
   *
   * @param Expression ...$element the Expression to add.
   * @return self
   */
  public function addExpressionToSequence( Expression $expression ) {
    $this->right = new static( $this->right, $expression );
  }

  /**
   * Adds the specified element to this infix expression. The right hand
   * expression is replaced with a new instance of this infix expression made
   * with the existing right hand expression and the suppled one.
   *
   * e.g. adding z to the expression x AND y would result in x AND y AND z.
   *
   * @param Expression ...$expression the Expressions to add.
   * @return self
   */
  public function addExpressionsToSequence() {
    $elements = func_get_args();
    if ( count( $elements ) != 0 ) {
      $right = array_pop( $elements );
      while ( $left = array_pop( $elements ) ) {
        $right = new static( $left, $right );
      }
      $this->right = new static( $this->right, $right );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = '';
    $string .= $this->left->build( $database );
    if ( $this->has_space_before ) {
      $string .= ' ';
    }
    $string .= $this->getSymbol();
    $string .= ' ';
    $string .= $this->right->build( $database );
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\InfixExpression::getLeftExpression()
   */
  public function getLeftExpression() {
    return $this->left;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\InfixExpression::getRightExpression()
   */
  public function getRightExpression() {
    return $this->right;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\InfixExpression::toArray()
   */
  public function toArray() {
    $result = array();
    $this->toArrayRecurse( $result );
    return $result;
  }

  /**
   * This method supports the toArray method by recursing over the entire infix
   * sequence, adding each expression to the specified array.
   *
   * @param array $result the array to add each expression in this infix
   * sequence to.
   */
  protected function toArrayRecurse( array &$result ) {
    if ( $this->left instanceof self ) {
      $this->left->toArrayRecurse( $result );
    } else {
      $result[] = $this->left;
    }
    if ( $this->right instanceof self ) {
      $this->right->toArrayRecurse( $result );
    } else {
      $result[] = $this->right;
    }
  }

}
