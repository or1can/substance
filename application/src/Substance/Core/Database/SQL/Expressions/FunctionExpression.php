<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 - 2015 Kevin Rogers
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
use Substance\Core\Database\SQL\Expression;

/**
 * A name expression for use in a SQL query.
 */
class FunctionExpression extends AbstractExpression {

  /**
   * @var string the function name.
   */
  protected $name;

  /**
   * @var Expression[] the function arguments.
   */
  protected $arguments = array();

  /**
   * Constructs a new function expression.
   *
   * @param string $name the function name.
   * @param Expression ...$expression the argument expressions.
   */
  public function __construct( $name ) {
    $this->name = $name;
    for ( $i = 1; $i < func_num_args(); $i++ ) {
      $expr = func_get_arg( $i );
      if ( $expr instanceof Expression ) {
        $this->arguments[] = $expr;
      } else {
        throw IllegalValueAlert::illegal_value('Can only add Expressions as function arguments')
          ->culprit( 'argument', $expr );
      }
    }
  }

  public function __toString() {
    $string = '';
    $string .= $this->name;
    $string .= '( ';
    $glue = '';
    foreach ( $this->arguments as $argument ) {
      $string .= $glue;
      $string .= (string) $argument;
      $glue = ', ';
    }
    $string .= ' )';
    return $string;

  }

  /**
   * Add an argument to this function call.
   *
   * @param Expression $expression the function argument to add.
   */
  public function addArgument( Expression $expression ) {
    $this->arguments[] = $expression;
  }

  /**
   * Add multiple arguments to this function call.
   *
   * @param Expression ...$expression the function arguments to add.
   */
  public function addArguments( Expression $expression ) {
    for ( $i = 0; $i < func_num_args(); $i++ ) {
      $expr = func_get_arg( $i );
      if ( $expr instanceof Expression ) {
        $this->arguments[] = $expr;
      } else {
        throw IllegalValueAlert::illegal_value('Can only add Expressions as function arguments')
          ->culprit( 'argument', $expr );
      }
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    return $database->buildFunctionExpression( $this );
  }

  /**
   * Returns an array of this functions arguments.
   *
   * @return Expression[] the function arguments.
   */
  public function getArguments() {
    return $this->arguments;
  }

  /**
   * Returns the function name.
   *
   * @return string the function name.
   */
  public function getName() {
    return $this->name;
  }

}
