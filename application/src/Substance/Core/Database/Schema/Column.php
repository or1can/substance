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

namespace Substance\Core\Database\Schema;

/**
 * Represents a column in a database table.
 */
interface Column {

  /**
   * Returns TRUE if this column allows NULL values and FALSE otherwise.
   *
   * @return boolean TRUE if NULL values are allowed and FALSE otherwise.
   */
  public function allowsNull();

  /**
   * Drop this column.
   *
   * @return self
   */
  public function drop();

  /**
   * Returns the default value for this column.
   *
   * @return mixed the default value for this column.
   */
  public function getDefault();

  /**
   * Returns the column name.
   *
   * @return string the column name.
   */
  public function getName();

  /**
   * Returns the table this column belongs to.
   *
   * @return Table the parent table for this column.
   */
  public function getTable();

  /**
   * Returns this columns type.
   *
   * @return Type the column type.
   */
  public function getType();

  /**
   * Sets the default value for this column.
   *
   * @param mixed $value the default value for this column.
   * @return self
   */
  public function setDefault( $value );

  /**
   * Sets the column name.
   *
   * @param string $name the column name.
   * @return self
   */
  public function setName( $name );

  /**
   * Sets this columns type.
   *
   * @param Type $type the column type.
   * @return self
   */
  public function setType( Type $type );

}
