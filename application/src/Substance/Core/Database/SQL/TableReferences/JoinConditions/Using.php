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

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\Components\ComponentList;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\TableReferences\JoinCondition;

/**
 * Represents an USING join condition.
 */
class Using implements JoinCondition {

  /**
   * @var ComponentList the using condition component list.
   */
  protected $columns;

  /**
   * Constructs a USING join condition on the specific column.
   *
   * @param ColumnNameExpression $name the column name
   */
  public function __construct( ColumnNameExpression $name ) {
    $this->columns = new ComponentList();
    $this->columns->add( $name );
  }

  /**
   * Builds a USING join condition on the specified columns.
   *
   * @param ColumnNameExpression ...$name one or more column names
   * @return Using a new USING condition on the specified columns.
   */
  public static function using() {
    return self::usingArray( func_get_args() );
  }

  public static function usingArray( array $columns ) {
    if ( count( $columns ) == 0 ) {
      // TODO - Would an Illegal argument alert be useful?
      throw Alert::alert( 'Illegal argument', 'At least one column name required' );
    } else {
      $using = NULL;
      foreach ( $columns as $column ) {
        if ( $column instanceof ColumnNameExpression ) {
          if ( is_null( $using ) ) {
            $using = new Using( $column );
          } else {
            $using->addColumnName( $column );
          }
        } else {
          // TODO - Would an Illegal argument alert be useful?
          throw Alert::alert( 'Illegal argument', 'Only column names can be used in a USING condition' )
          ->culprit( 'arg', $column );
        }
      }
      return $using;
    }
  }

  public function __toString() {
    $string = 'USING ( ';
    $string .= (string) $this->columns;
    $string .= ' )';
    return $string;
  }

  /**
   * Adds a column name to this condition.
   *
   * @param ColumnNameExpression $name the column name to add to this condition.
   */
  public function addColumnName( ColumnNameExpression $name ) {
    $this->columns->add( $name );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = 'USING ( ';
    $string .= $this->columns->build( $database );
    $string .= ' )';
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::define()
   */
  public function define( Query $query ) {
    // Nothing to do.
  }

}
