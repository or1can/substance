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
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\TableReferences\JoinCondition;

/**
 * Represents an ON join condition.
 */
class On implements JoinCondition {

  /**
   * @var Expression the on condition expression.
   */
  protected $expression;

  public function __construct( Expression $expr ) {
    $this->expression = $expr;
  }

  public function __toString() {
    $string = 'ON ';
    $string .= (string) $this->expression;
    return $string;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::aboutToAddQuery()
   */
  public function aboutToAddQuery( Query $query ) {
    // Nothing to do.
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    $string = 'ON ';
    // FIXME - This should probably be wrapped in parenthesis.
    $string .= $this->expression->build( $database );
    return $string;
  }

}
