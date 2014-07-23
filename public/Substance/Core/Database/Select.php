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

namespace Substance\Core\Database;

/**
 * Represents a SELECT database query in Substance.
 */
interface Select {

  /**
   * Returns the first record with specified conditions.
   *
   * SELECT * FROM table WHERE ($conditions match) LIMIT 1
   *
   * @param array $conditions associative array of column-value pairs for the
   * record to find.
   * @return self
   */
  public function find( array $conditions );

  /**
   * Returns a PDO statment for all records matching the specified conditions.
   *
   * SELECT * FROM table WHERE ($conditions match)
   *
   * @param array $conditions associative array of column-value pairs for the
   * records to find.
   * @return \PDOStatement
   */
  public function findAll( array $conditions );

  /**
   * Returns the record with the specified primary key.
   *
   * SELECT * FROM table WHERE table.primary_key = $id
   *
   * @param unknown $id Primary key of record to find.
   * @return self
   */
  public function findByPrimaryKey( $id );

  public function groupBy( $column );

  public function having( Condition $condition );

  public function innerJoin();

  /**
   * Adds a condition checking if the specified column is NULL.
   *
   * Shorthand for
   *
   * Select->where( NullCondition::isNull( $column ) );
   *
   * @param string $column the column to check for a NULL value.
   */
  public function isNull( $column );

  /**
   * Adds a condition checking if the specified column is NOT NULL.
   *
   * Shorthand for
   *
   * Select->where( NullCondition::isNotNull( $column ) );
   *
   * @param string $column the column to check for a NOT NULL value.
   */
  public function isNotNull( $column );

  public function join();

  public function leftJoin();

  public function limit( $limit );

  public function orderBy( $column, $direction = 'ASC' );

  public function where( Condition $condition );

}
