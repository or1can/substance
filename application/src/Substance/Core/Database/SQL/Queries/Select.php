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

namespace Substance\Core\Database\SQL\Queries;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Column;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Component;
use Substance\Core\Database\SQL\Components\ComponentList;
use Substance\Core\Database\SQL\Components\OrderBy;
use Substance\Core\Database\SQL\Expression;
use Substance\Core\Database\SQL\Expressions\AndExpression;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\TableReference;
use Substance\Core\Database\SQL\TableReferences\InnerJoin;
use Substance\Core\Database\SQL\TableReferences\JoinCondition;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\On;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\Using;
use Substance\Core\Database\SQL\TableReferences\LeftJoin;
use Substance\Core\Database\SQL\TableReferences\TableName;

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
   * @var ComponentList the list of expressions making up the group by.
   */
  protected $group_by;

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
   * @var ComponentList the list of expressions making up the order by
   */
  protected $order_by;

  /**
   * @var ComponentList the list of columns making up the select list.
   */
  protected $select_list;

  /**
   * @var TableReference The source we are selecting data from
   */
  protected $table;

  /**
   * @var Expression where clause expression
   */
  protected $where = NULL;

  /**
   * Construct a new SELECT query to select data from the specified table.
   *
   * @param TableReference $table the table to select data from.
   */
  public function __construct( TableReference $table ) {
    $this->select_list = new ComponentList();
    $this->table = $table;
    // Define this table in the query, so other joins do not clash with it.
    $this->table->define( $this );
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
   * Adds a column to the select list.
   *
   * @param Column $column the column to add
   * @return self
   */
  public function addColumn( Column $column ) {
    $column->aboutToAddQuery( $this );
    $this->select_list->add( $column );
    return $this;
  }

  /**
   * Adds a column by name to the select list.
   *
   * @param string $name the name of the column to add.
   * @param string $alias the alias for the column.
   * @param string $table the table name or alias of the table containing the
   * column.
   * @return self
   */
  public function addColumnByName( $name, $alias = NULL, $table = NULL ) {
    return $this->addExpression( new ColumnNameExpression( $name, $table ), $alias );
  }

  /**
   * Adds an expression as a column to the select list.
   *
   * @param Expression $expression the expression to add a column for.
   * @param string $alias the alias for the column or NULL for no alias.
   * @return self
   */
  public function addExpression( Expression $expression, $alias = NULL ) {
    if ( isset( $alias ) ) {
      $this->addColumn( new ColumnWithAlias( $expression, $alias ) );
    } else {
      $this->addColumn( $expression );
    }
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Query::build()
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
      $this->group_by = new ComponentList();
    }
    $expression->aboutToAddQuery( $this );
    $this->group_by->add( $expression );
    return $this;
  }

  /**
   * Adds an expression to the having clause.
   *
   * @param Expression $expression the expression to add to the having clause.
   * @return self
   */
  public function having( Expression $expression ) {
    $expression->aboutToAddQuery( $this );
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

  /**
   * Adds an inner join to the specified table at the end of the from clause.
   *
   * @param string $table the table name.
   * @param string $alias the table name alias or NULL for no alias.
   * @param JoinCondition $condition the join condition, or NULL for no
   * condition.
   * @return self
   */
  public function innerJoin( $table, $alias = NULL, JoinCondition $condition = NULL ) {
    $right_table = new TableName( $table, $alias );
    // Define the new table in the query, so other joins do not clash with it.
    $right_table->define( $this );
    if ( isset( $condition ) ) {
      $condition->aboutToAddQuery( $this );
    }
    $this->table = new InnerJoin( $this->table, $right_table, $condition );
    return $this;
  }

  /**
   * Adds an inner join to the specified table, using the specified expression
   * in an ON clause.
   *
   * @param string $table the table name
   * @param string $alias the table name alias or NULL for no alias.
   * @param Expression $expression the ON join condition expression
   * @return self
   */
  public function innerJoinOn( $table, $alias, Expression $expression ) {
    $this->innerJoin( $table, $alias, new On( $expression ) );
    return $this;
  }

  /**
   * Adds an inner join to the specified table, using the specified expression
   * in an USING clause.
   *
   * @param string $table the table name
   * @param string $alias the table name alias or NULL for no alias.
   * @param ColumnNameExpression ...$name one or more column names for the
   * USING expression
   * @return self
   */
  public function innerJoinUsing( $table, $alias ) {
    // To get the column names, first get all the arguments and then remove the
    // table and alias from the front. Anything left over are the names.
    $names = func_get_args();
    array_shift( $names );
    array_shift( $names );
    $this->innerJoin(
      $table,
      $alias,
      Using::usingArray( $names )
    );
    return $this;
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

  /**
   * Adds a left join to the specified table at the end of the from clause.
   *
   * @param string $table the table name
   * @param string $alias the table name alias or NULL for no alias.
   * @param JoinCondition $condition the join condition, or NULL for no
   * condition.
   * @return self
   */
  public function leftJoin( $table, $alias = NULL, JoinCondition $condition = NULL ) {
    $right_table = new TableName( $table, $alias );
    // Define the new table in the query, so other joins do not clash with it.
    $right_table->define( $this );
    if ( isset( $condition ) ) {
      $condition->aboutToAddQuery( $this );
    }
    $this->table = new LeftJoin( $this->table, $right_table, $condition );
    return $this;
  }

  /**
   * Adds a left join to the specified table, using the specified expression
   * in an ON clause.
   *
   * @param string $table the table name
   * @param string $alias the table name alias or NULL for no alias.
   * @param Expression $expression the ON join condition expression
   * @return self
   */
  public function leftJoinOn( $table, $alias, Expression $expression ) {
    $this->leftJoin( $table, $alias, new On( $expression ) );
    return $this;
  }

  /**
   * Adds a left join to the specified table, using the specified expression
   * in an USING clause.
   *
   * @param string $table the table name
   * @param string $alias the table name alias or NULL for no alias.
   * @param ColumnNameExpression ...$name one or more column names for the
   * USING expression
   * @return self
   */
  public function leftJoinUsing( $table, $alias ) {
    // To get the column names, first get all the arguments and then remove the
    // table and alias from the front. Anything left over are the names.
    $names = func_get_args();
    array_shift( $names );
    array_shift( $names );
    $this->leftJoin(
      $table,
      $alias,
      Using::usingArray( $names )
    );
    return $this;
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
   * Expressions are added to the end of the list of order terms., e.g.
   *
   *     $select = new Select(...);
   *     $select->orderBy( new ColumnNameExpression('col1'), 'ASC' );
   *     $select->orderBy( new ColumnNameExpression('col2'), 'DESC' );
   * would generate the following SQL:
   *     SELECT ... ORDER BY col1 ASC, col2 DESC
   *
   * @param Expression $expression the expression to sort by.
   * @param string $direction the sort direction for this expression
   * @return self
   */
  public function orderBy( Expression $expression, $direction = 'ASC' ) {
    if ( is_null( $this->order_by ) ) {
      $this->order_by = new ComponentList();
    }
    $expression->aboutToAddQuery( $this );
    $this->order_by->add( new OrderBy( $expression, $direction ) );
    return $this;
  }

  /**
   * Adds the expressions to the order by clause.
   *
   * This is a convenience for adding an array of expressions to the order by
   * clause. Each expression in the supplied array is added in sequence using
   * the specified direction, e.g.
   *     $select = new Select(...);
   *     $order_by = array(
   *         new ColumnNameExpression('col1'),
   *         new ColumnNameExpression('col2'),
   *         new ColumnNameExpression('col3'),
   *     );
   *     $select->orderByExpressions( $order_by, 'ASC' );
   * would generate the following SQL:
   *     SELECT ... ORDER BY col1 ASC, col2 ASC, col3 ASC
   *
   * @param Expression[] $expressions the expressions to sort by.
   * @param string $direction the sort direction for this expression
   * @return self
   */
  public function orderByExpressions( array $expressions, $direction = 'ASC' ) {
    // The supplied expression is a comma expression, so we should add each
    // one in turn to the order.
    foreach ( $expressions as $expression ) {
      $this->orderBy( $expression, $direction );
    }
    return $this;
  }

  /**
   * Adds an expression to the where clause.
   *
   * @param Expression $expression the expression to add to the where clause.
   * @return self
   */
  public function where( Expression $expression ) {
    $expression->aboutToAddQuery( $this );
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
