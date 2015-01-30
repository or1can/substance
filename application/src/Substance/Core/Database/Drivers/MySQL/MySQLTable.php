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

namespace Substance\Core\Database\Drivers\MySQL;

use Substance\Core\Database\Schema\AbstractTable;

/**
 * Concrete schema Table instance for working with a table in a MySQL database.
 */
class MySQLTable extends AbstractTable {

  protected $information_schema_row;

  /**
   * Construct a new table object to work with the specified table in the
   * connected database.
   *
   * @param Connection $connection the database to work with.
   * @param object $row the information_schema table row for this table.
   */
  public function __construct( $connection, $row ) {
    parent::__construct( $connection );
    $this->information_schema_row = $row;
    $this->setName( $this->information_schema_row->TABLE_NAME );
  }

}