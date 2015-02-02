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
use Substance\Core\Database\SQL\Query;

/**
 * A equals expression, representing an equality test of a left and right
 * expression.
 */
class EqualsExpression extends AbstractExpression {

  /**
   * @var string the expression alias.
   */
  protected $alias;

  /**
   * @var Expression the left expression.
   */
  protected $left;

    /**
   * @var Expression the right expression.
   */
  protected $right;

  public function __construct( Expression $left, Expression $right, $alias = NULL ) {
    $this->alias = $alias;
    $this->left = $left;
    $this->right = $right;
  }

  public function __toString() {
    $string = '';
    $string .= $this->left;
    $string .= ' = ';
    $string .= $this->right;
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
  	$string = '';
    $string .= $this->left->build( $database );
    $string .= ' = ';
    $string .= $this->right->build( $database );
  	if ( isset( $this->alias ) ) {
  	  $string .= ' AS ';
      $string .= $database->quoteName( $this->alias );
  	}
  	return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::define()
   */
  public function define( Query $query ) {
    $this->left->define( $query );
    $this->right->define( $query );
  }

}
