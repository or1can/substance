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

use Substance\Core\Alert\Alerts\IllegalValueAlert;
use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\Query;

/**
 * A literal expression for strings, numbers and booleans in a SQL query.
 */
class LiteralExpression extends AbstractExpression {

  /**
   * @var string the argument placeholder.
   */
  protected $placeholder;

  /**
   * @var string the literal value.
   */
  protected $value;

  public function __construct( $value ) {
    if ( self::is_supported( $value ) ) {
      $this->value = $value;
    } else {
      // TODO - Would an Illegal argument alert be useful?
      throw IllegalValueAlert::illegal_value( 'Only null, boolean, string, integer and float types are allowed' )
        ->culprit( 'value', $value )
        ->culprit( 'type', gettype( $value ) );
    }
  }

  public function __toString() {
    $string = '';
    if ( is_null( $this->value ) ) {
      $string = 'NULL';
    } elseif ( is_bool( $this->value ) ) {
      $string = $this->value ? 'TRUE' : 'FALSE';
    } elseif ( is_string( $this->value ) ) {
      $string = $this->value;
    } elseif ( is_int( $this->value ) ) {
      $string = $this->value;
    } elseif ( is_float( $this->value ) ) {
      $string = $this->value;
    }
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    return $database->buildLiteralExpression( $this );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::define()
   */
  public function define( Query $query ) {
    $this->placeholder = $query->defineArgument( $this );
  }

  /**
   * Returns the placeholder for this literal expression.
   *
   * The placeholder is used for dynamic values in queries, where the database
   * handles quoting and escaping values to avoid SQL injection issues.
   *
   * @return string the placeholder or NULL if there is no placeholder.
   */
  public function getPlaceHolder() {
    return $this->placeholder;
  }

  /**
   * Returns this literal expressions value.
   *
   * @return mixed the literal value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Checks if the specified value is of a type supported as a literal.
   *
   * @param unknown $value the value to test.
   * @return boolean TRUE if the value is of a supported type and FALSE
   * otherwise.
   */
  public static function is_supported( $value ) {
    return is_null( $value ) || is_bool( $value ) || is_string( $value ) || is_int( $value ) || is_float( $value );
  }

}
