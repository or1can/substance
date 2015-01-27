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

namespace Substance\Core\Database\SQL\TableReferences\JoinConditions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\CommaExpression;
use Substance\Core\Database\SQL\TableReferences\JoinCondition;
use Substance\Core\Alert\Alert;

/**
 * Represents an USING join condition.
 */
class Using implements JoinCondition {

  /**
   * @var Expression the using condition expression.
   */
  protected $expression;

  /**
   * Constructs a USING join condition on the specific column.
   *
   * @param ColumnNameExpression $name the column name
   */
  public function __construct( ColumnNameExpression $name ) {
    $this->expression = $name;
  }

  public function __toString() {
    $string = 'USING ( ';
    $string .= (string) $this->expression;
    $string .= ' )';
    return $string;
  }

  /**
   * Adds a column name to this condition.
   *
   * @param ColumnNameExpression $name the column name to add to this condition.
   */
  public function addColumnName( ColumnNameExpression $name ) {
    if ( $this->expression instanceof CommaExpression ) {
      $this->expression->addExpressionToSequence( $name );
    } else {
      $this->expression = new CommaExpression( $this->expression, $name );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = 'USING ( ';
    $string .= $this->expression->build( $database );
    $string .= ' )';
    return $string;
  }

  /**
   * Builds a USING join condition on the specified columns.
   *
   * @param ColumnNameExpression ...$name one or more column names
   * @return Using a new USING condition on the specified columns.
   */
  public static function using() {
    $args = func_get_args();
    if ( count( $args ) == 0 ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'At least one column name required' );
    } else {
      $using = NULL;
      foreach ( $args as $arg ) {
        if ( $arg instanceof ColumnNameExpression ) {
          if ( is_null( $using ) ) {
            $using = new Using( $arg );
          } else {
            $using->addColumnName( $arg );
          }
        } else {
          // TODO - Would an Illegal argument alert be useful?
          throw Alert::alert( 'Illegal argument', 'Only column names can be used in a USING condition' )
            ->culprit( 'arg', $arg );
        }
      }
      return $using;
    }
  }

}
