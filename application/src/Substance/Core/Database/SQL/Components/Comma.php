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

namespace Substance\Core\Database\SQL\Components;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Component;

/**
 * Represents a series of components separated by a comma in a database query.
 *
 * e.g. the:
 *     a, b
 * in
 *     SELECT a, b FROM table
 */
class Comma implements Component {

  /**
   * @var Component the left component.
   */
  protected $left;

  /**
   * @var Component the right component.
   */
  protected $right;

  public function __construct( Component $left, Component $right ) {
    $this->left = $left;
    $this->right = $right;
  }

  public function __toString() {
    $string = '';
    $string .= $this->left;
    $string .= ', ';
    $string .= $this->right;
    return $string;
  }

  /**
   * Adds the specified component to this comma component. The right hand
   * component is replaced with a new comma component made with the existing
   * right hand component and the suppled one.
   *
   * e.g. adding z to x, y would result in x, y, z.
   *
   * @param Component ...$component the component to add.
   * @return self
   */
  public function addComponentToSequence( Component $component ) {
    $this->right = new static( $this->right, $component );
  }

  /**
   * Adds the specified components to this comma component. The right hand
   * expression is replaced with a new instance of this infix expression made
   * with the existing right hand expression and the suppled one.
   *
   * e.g. adding x and z to the expression x, y would result in x, y, x, z.
   *
   * @param Component ...$component the component to add.
   * @return self
   */
  public function addComponentsToSequence() {
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
    $string .= ', ';
    $string .= $this->right->build( $database );
    return $string;
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
   * sequence, adding each component to the specified array.
   *
   * @param array $result the array to add each compnoent in this comma
   * component sequence to.
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
