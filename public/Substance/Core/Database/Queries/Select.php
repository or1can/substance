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

namespace Substance\Core\Database\Queries;

use Substance\Core\Database\Database;
use Substance\Core\Database\Expression;
use Substance\Core\Database\Expressions\AndExpression;
use Substance\Core\Database\Expressions\CommaExpression;
use Substance\Core\Database\Expressions\OrderByExpression;
use Substance\Core\Database\Expressions\SelectListExpression;
use Substance\Core\Database\Expressions\TableNameExpression;
use Substance\Core\Database\Query;

/**
 * Represents a SELECT database query.
 */
class Select extends Query {

  /**
   * @var boolean Indicates if this is a DISTINCT query, TRUE for DISTINCT and
   * FALSE for ALL.
   */
  protected $distinct = FALSE;

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
   * @var Expression order by expression.
   */
  protected $order_by = NULL;

  /**
   * @var SelectListExpression select list expression.
   */
  protected $select_list;

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
   * @param string $alias the alias for the table being selected from
   */
  public function __construct( $table, $alias = NULL ) {
    $this->select_list = new SelectListExpression();
    $table_expr = new TableNameExpression( $table, $alias );
    // FIXME - This is wrong, as we shouldn't be about to add the table
    // expression to the select list.
    $table_expr->aboutToAddQuery( $this, $this->select_list );
    $this->table = $table_expr;
  }

  public function __toString() {
    $sql = 'SELECT ';
    if ( $this->distinct ) {
      $sql .= 'DISTINCT ';
    }
    $sql .= (string) $this->select_list;
    $sql .= ' FROM ';
    $sql .= (string) $this->table;
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
    if ( !is_null( $this->order_by ) ) {
      $sql .= ' ORDER BY ';
      $sql .= (string) $this->order_by;
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
    $this->select_list->add( $this, $expression );
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Query::build()
   */
  public function build( Database $database ) {
    $sql = "SELECT ";
    if ( $this->distinct ) {
      $sql .= 'DISTINCT ';
    }
    $sql .= $this->select_list->build( $database );
    $sql .= ' FROM ';
    $sql .= $this->table->build( $database );
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
    if ( !is_null( $this->order_by ) ) {
      $sql .= ' ORDER BY ';
      $sql .= $this->order_by->build( $database );
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
   * Used to make this select return distinct rows only.
   *
   * This controls the use of DISTINCT or ALL in the SELECT query. When set to
   * TRUE, a SELECT DISTINCT query will be generated and when set to FALSE a
   * SELECT ALL query will be generated.
   *
   * @param boolean $distinct TRUE to return distinct rows only and FALSE to
   * return all rows.
   * @return self
   * @see Select::all()
   */
  public function distinct( $distinct = TRUE ) {
    $this->distinct = $distinct;
    return $this;
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
   * @return self
   */
  public function limit( $limit ) {
    $this->limit = $limit;
    return $this;
  }

  /**
   * Sets the number of rows that should be omitted from start of this queries
   * result set.
   *
   * @param integer $offset the number of rows to omit from the start of the
   * result set.
   * @return self
   */
  public function offset( $offset ) {
    $this->offset = $offset;
    return $this;
  }

  /**
   * Adds an expression to the order by clause.
   *
   * If the expression is an OrderByExpression, it will be added ignoring the
   * direction parameter, e.g.
   *     $select = new Select(...);
   *     $order_by = new OrderByExpression( new ColumnNameExpression('col1'), 'DESC' );
   *     $select->orderBy( $order_by, 'ASC' );
   * would generate the following SQL:
   *     SELECT ... ORDER BY col1 DESC
   *
   * If the expression is a CommaExpression, it will be expanded and each
   * expression in its sequence will be added to the order by clause by
   * recursively calling this method, e.g.
   *     $select = new Select(...);
   *     $order_by = new CommaExpression(
   *         new ColumnNameExpression('col1'),
   *         new OrderByExpression( new ColumnNameExpression('col2'), 'DESC' )
   *     );
   *     $order_by->addExpressionToSequence(
   *         new ColumnNameExpression('col3'),
   *     );
   *     $select->orderBy( $order_by, 'ASC' );
   * would generate the following SQL:
   *     SELECT ... ORDER BY col1 ASC, col2 DESC, col3 ASC
   *
   * If the expression is any other kind of Expression, it will be added as is
   * using the specified direction., e.g.
   *     $select = new Select(...);
   *     $select->orderBy( new ColumnNameExpression('col1'), 'ASC' );
   *     $select->orderBy( new ColumnNameExpression('col2'), 'DESC' );
   * would generate the following SQL:
   *     SELECT ... ORDER BY col1 ASC, col2 DESC
   *
   * @param Expression $expression the expression to sort by. If this is a
   * OrderByExpression, it will be added to the order by clause using the
   * direction in the OrderByExpression and ignoring the direction parameter
   * below. If this is a CommaExpression, it will be expanded into it's
   * sequence of expressions and each one will be added by calling this method
   * using the direction parameter below. If this is any other kind of
   * expression, it will be added as is using the direction parameter below.
   * @param string $direction the sort direction for this expression
   * @return self
   */
  public function orderBy( Expression $expression, $direction = 'ASC' ) {
    if ( $expression instanceof OrderByExpression ) {
      // The supplied expression is an order expression, so ignore any supplied
      // direction and add to the list.
      $this->orderByAddExpression( $expression );
    } elseif ( $expression instanceof CommaExpression ) {
      // The supplied expression is a comma expression, so we should add each
      // one in turn to the order.
      foreach ( $expression->toArray() as $expression ) {
        $this->orderBy( $expression, $direction );
      }
    } else {
      // It's some other expression, so just wrap it in an order by expression
      // and add it to the list.
      $this->orderByAddExpression( new OrderByExpression( $expression, $direction ) );
    }
    return $this;
  }

  /**
   * Adds an OrderByExpression to the order by clause. This is a convenience
   * function used to internally to avoid code duplication.
   *
   * @param OrderByExpression $expression the order by expression to sort by
   */
  protected function orderByAddExpression( OrderByExpression $expression ) {
    if ( is_null( $this->order_by ) ) {
      $this->order_by = $expression;
    } elseif ( $this->order_by instanceof CommaExpression ) {
      $this->order_by->addExpressionToSequence( $expression );
    } else {
      $this->order_by = new CommaExpression( $this->order_by, $expression );
    }
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
