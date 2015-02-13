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

namespace Substance\Core\Database\Schema;

use Substance\Core\Alert\Alert;

/**
 * Represents a database table in Substance.
 */
interface Table {

  /**
   * Adds the specified column to the table.
   *
   * @param Column $column the column to add to the table.
   * @return self
   */
  public function addColumn( Column $column );

  /**
   * Adds the specified index to the table.
   *
   * @param Index $index the index to add to the table.
   * @return self
   */
  public function addIndex( Index $index );

  /**
   * Drops this table.
   *
   * @return self
   */
  public function drop();

  /**
   * Returns the specified column.
   *
   * @param string $name the name of the column.
   * @return Column the column with the specified name.
   * @throws Alert if there is no column with the specified name.
   */
  public function getColumn( $name );

  /**
   * Returns the specified index.
   *
   * @param string $name the name of the index
   * @return Index the index with the specified name.
   * @throws Alert if there is no index with the specified name.
   */
  public function getIndex( $name );

  /**
   * Returns the table name.
   *
   * @return string the table name.
   */
  public function getName();

  /**
   * Checks if a column with the specified name exists.
   *
   * @param string $name the name of the column.
   * @return boolean TRUE if a column with the specified name exists and FALSE
   * otherwise.
   */
  public function hasColumnByName( $name );

  /**
   * Checks if an index with the specified name exists.
   *
   * @param string $name the name of the index.
   * @return boolean TRUE if an index with the specified name exists and FALSE
   * otherwise.
   */
  public function hasIndexByName( $name );

  /**
   * Returns an array of this tables columns.
   *
   * @return Column[] an array of this tables columns.
   */
  public function listColumns();

  /**
   * Returns an array of this tables indexes.
   *
   * @return Index[] an array of this tables indexes.
   */
  public function listIndexes();

  /**
   * Sets the table name.
   *
   * @param string $name the table name.
   * @return self
   */
  public function setName( $name );

}
