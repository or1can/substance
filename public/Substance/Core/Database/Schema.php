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

use Substance\Core\Database\Schema\Table;

/**
 * The Schema class is used for working with a database schema.
 */
abstract class Schema {

  /**
   * @var Connection the database connection we are working with.
   */
  protected $connection;

  /**
   * Construct a new schema object to work with the specified connected
   * database.
   *
   * @param Connection $connection the database to work with
   */
  public function __construct( Connection $connection ) {
    $this->connection = $connection;
  }

  /**
   * Creates a database with the specified name in the server specified in this
   * Schema's connection.
   *
   * @param string $name the new database name.
   */
  abstract public function createDatabases( $name );

  /**
   * Creates a table with the specified name in the database specified in this
   * Schema's connection.
   *
   * @param string $name the new table name.
   */
  abstract public function createTable( $name );

  /**
   * Lists the available databases on the server.
   *
   * @return Database[] associative array of database name to Database objects.
   */
  abstract public function listDatabases();

  /**
   * Lists the tables in the database.
   *
   * @return Table[] associative array of table name to Table objects.
   */
  abstract public function listTables();

}
