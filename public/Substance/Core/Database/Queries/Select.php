<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
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

namespace Substance\Core\Database\Queries;

use Substance\Core\Database\Database;
use Substance\Core\Database\Expression;
use Substance\Core\Database\Expressions\AndExpression;
use Substance\Core\Database\Query;
use Substance\Core\Database\Expressions\CommaExpression;

/**
 * Represents a SELECT database query.
 */
class Select extends Query {

  /**
   * @var Expression group by expression.
   */
  protected $group_by = NULL;

  /**
   * @var Expression having expression.
   */
  protected $having = NULL;

  /**
   * The maximum number of rows that should be returned by this query.
   *
   * @var integer
   */
  protected $limit;

  /**
   * The number of rows that should be omitted from the start of this queries
   * result set.
   *
   * @var integer
   */
  protected $offset;

  /**
   * @var Expression select list expression.
   */
  protected $select_list = NULL;

  /**
   * The table we are selecting data from.
   *
   * @var string
   */
  protected $table;

  /**
   * @var Expression where clause expression
   */
  protected $where = NULL;

  /**
   * Construct a new SELECT query to select data from the specified table.
   *
   * @param string $table the table to select data from.
   */
  public function __construct( $table ) {
    $this->table = $table;
  }

  public function __toString() {
    $sql = "SELECT ";
    if ( is_null( $this->select_list ) ) {
      $sql .= '/* No select expressions */';
    } else {
      $sql .= (string) $this->select_list;
    }
    foreach ( $this->select_list as $expression ) {
      $sql .= (string) $expression;
    }
    $sql .= ' FROM ';
    $sql .= $this->table;
    if ( !is_null( $this->where ) ) {
      $sql .= ' WHERE ';
      $sql .= (string) $this->where;
    }
    if ( !is_null( $this->group_by ) ) {
      $sql .= ' GROUP BY ';
      $sql .= (string) $this->group_by;
      if ( !is_null( $this->having ) ) {
        $sql .= ' HAVING ';
        $sql .= (string) $this->having;
      }
    }
    if ( isset( $this->limit ) ) {
      $sql .= ' LIMIT ';
      $sql .= $this->limit;
      if ( isset( $this->offset ) ) {
        $sql .= ' OFFSET ';
        $sql .= $this->offset;
      }
    }
    return $sql;
  }

  /**
   * Adds an expression to the select list
   *
   * @param Expression $expression
   * @return self
   */
  public function addExpression( Expression $expression ) {
    if ( is_null( $this->select_list ) ) {
      $this->select_list = $expression;
    } elseif ( $this->select_list instanceof CommaExpression ) {
      $this->select_list->addExpressionToSequence( $expression );
    } else {
      $this->select_list = new CommaExpression( $this->select_list, $expression );
    }
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Query::build()
   */
  public function build( Database $database ) {
    $sql = "SELECT ";
    if ( is_null( $this->select_list ) ) {
      $sql .= '/* No select expressions */';
    } else {
      $sql .= $this->select_list->build( $database );
    }
    $sql .= ' FROM ';
    $sql .= $database->quoteTable( $this->table );
    if ( !is_null( $this->where ) ) {
      $sql .= ' WHERE ';
      $sql .= $this->where->build( $database );
    }
    if ( !is_null( $this->group_by ) ) {
      $sql .= ' GROUP BY ';
      $sql .= $this->group_by->build( $database );
      if ( !is_null( $this->having ) ) {
        $sql .= ' HAVING ';
        $sql .= $this->having->build( $database );
      }
    }
    if ( isset( $this->limit ) ) {
      $sql .= ' LIMIT ';
      $sql .= $this->limit;
      if ( isset( $this->offset ) ) {
        $sql .= ' OFFSET ';
        $sql .= $this->offset;
      }
    }
    return $sql;
  }

  /**
   * Adds an expression to the group by clause.
   *
   * @param Expression $expression the expression to add to the group by
   * clause.
   * @return self
   */
  public function groupBy( Expression $expression ) {
    if ( is_null( $this->group_by ) ) {
      $this->group_by = $expression;
    } elseif ( $this->group_by instanceof CommaExpression ) {
      $this->group_by->addExpressionToSequence( $expression );
    } else {
      $this->group_by = new CommaExpression( $this->group_by, $expression );
    }
    return $this;
  }

  /**
   * Adds an expression to the having clause.
   *
   * @param Expression $expression the expression to add to the having clause.
   * @return self
   */
  public function having( Expression $expression ) {
    if ( is_null( $this->having ) ) {
      $this->having = $expression;
    } elseif ( $this->having instanceof AbstractInfixExpression ) {
      $this->having->addExpressionToSequence( $expression );
    } else {
      // TODO - Allow choice of AND or OR.
      $this->having = new AndExpression( $this->having, $expression );
    }
    return $this;
  }

  public function innerJoin() {
    // TODO
  }

  /**
   * Adds a condition checking if the specified column is NULL.
   *
   * Shorthand for
   *
   * Select->where( NullCondition::isNull( $column ) );
   *
   * @param string $column the column to check for a NULL value.
   */
  public function isNull( $column ) {
    // TODO
  }

  /**
   * Adds a condition checking if the specified column is NOT NULL.
   *
   * Shorthand for
   *
   * Select->where( NullCondition::isNotNull( $column ) );
   *
   * @param string $column the column to check for a NOT NULL value.
   */
  public function isNotNull( $column ) {
    // TODO
  }

  public function join() {
    // TODO
  }

  public function leftJoin() {
    // TODO
  }

  /**
   * Sets the limit on the number of rows this query will return.
   *
   * @param integer $limit the maximum number of rows that should be returned.
   */
  public function limit( $limit ) {
    $this->limit = $limit;
  }

  /**
   * Sets the number of rows that should be omitted from start of this queries
   * result set.
   *
   * @param integer $offset the number of rows to omit from the start of the
   * result set.
   */
  public function offset( $offset ) {
    $this->offset = $offset;
  }

  /**
   * Adds an expression to the order by clause.
   *
   * @param Expression $expression the expression to sort by
   * @param string $direction the sort direction for this expression
   */
  public function orderBy( Expression $expression, $direction = 'ASC' ) {
    // TODO
  }

  /**
   * Adds an expression to the where clause.
   *
   * @param Expression $expression the expression to add to the where clause.
   * @return self
   */
  public function where( Expression $expression ) {
    if ( is_null( $this->where ) ) {
      $this->where = $expression;
    } elseif ( $this->where instanceof AbstractInfixExpression ) {
      $this->where->addExpressionToSequence( $expression );
    } else {
      // TODO - Allow choice of AND or OR.
      $this->where = new AndExpression( $this->where, $expression );
    }
    return $this;
  }

}
