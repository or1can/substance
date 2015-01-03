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
use Substance\Core\Alert\Alert;

/**
 * A literal expression for strings, numbers and booleans in a SQL query.
 */
class LiteralExpression implements Expression {

  /**
   * @var string the column alias.
   */
  protected $alias;

  /**
   * @var string the literal value.
   */
  protected $value;

  public function __construct( $value, $alias = NULL ) {
    $this->alias = $alias;
    if ( self::is_supported( $value ) ) {
      $this->value = $value;
    } else {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'Only boolean, string, integer and float types are allowed' )
        ->culprit( 'type', gettype( $value ) );
    }
  }

  public function __toString() {
    $string = '';
    if ( is_bool( $this->value ) ) {
      $string = $this->value ? 'TRUE' : 'FALSE';
    } elseif ( is_string( $this->value ) ) {
      $string = $this->value;
    } elseif ( is_int( $this->value ) ) {
      $string = $this->value;
    } elseif ( is_float( $this->value ) ) {
      $string = $this->value;
    }
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $this->alias;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Expression::build()
   */
  public function build( Connection $connection ) {
  	$string = '';
    if ( is_bool( $this->value ) ) {
      $string = $this->value ? 'TRUE' : 'FALSE';
    } elseif ( is_string( $this->value ) ) {
      $string = $connection->quoteString( $this->value );
    } elseif ( is_int( $this->value ) ) {
      $string = $this->value;
    } elseif ( is_float( $this->value ) ) {
      $string = $this->value;
    }
    if ( isset( $this->alias ) ) {
      $string .= ' AS ';
      $string .= $connection->quoteName( $this->alias );
    }
    return $string;
  }

  /**
   * Checks if the specified value is of a type supported as a literal.
   *
   * @param unknown $value the value to test.
   * @return boolean TRUE if the value is of a supported type and FALSE
   * otherwise.
   */
  public static function is_supported( $value ) {
    return is_bool( $value ) || is_string( $value ) || is_int( $value ) || is_float( $value );
  }

}
