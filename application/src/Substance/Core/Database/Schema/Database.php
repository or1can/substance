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
use Substance\Core\Database\Connection;
use Substance\Core\Database\Schema\Table;
use Substance\Core\Database\SQL\DataDefinition;
use Substance\Core\Database\SQL\Query;

/**
 * Represents a database schema.
 */
interface Database {

  /**
   * Applies any outstanding data definitions.
   */
  protected function applyDataDefinitions();

  /**
   * Creates a table with the specified name in the database specified in this
   * Schema's connection.
   *
   * @param string $name the new table name.
   * @return Table the new table.
   */
  public function createTable( $name );

  /**
   * Drops the specified table.
   *
   * @param Table $table the table to drop.
   * @return self
   */
  public function dropTable( Table $table );

  /**
   * Execute the specified query on this database.
   *
   * @param Query $query the query to execute.
   * @return Statement the result statement.
   * @see Database::getConnection()
   */
  public function execute( Query $query );

  /**
   * Returns the connection to this database.
   *
   * @return Connection the underlying connection.
   */
  public function getConnection();

  /**
   * Returns the name of this database. If the underlying database system does
   * not support names, this will return NULL.
   *
   * @return string the database name or NULL if not applicable
   */
  public function getName();

  /**
   * Returns the specified table.
   *
   * This method will cause the table schema to be loaded and cached.
   *
   * @param string $name the name of the table.
   * @return Table the table with the specified name.
   * @throws Alert if there is no table with the specified name.
   */
  public function getTable( $name );

  /**
   * Checks if a table with the specified name exists.
   *
   * This method will cause the table schema to be loaded and cached.
   *
   * @param string $name the name of the table.
   * @return boolean TRUE if a table with the specified name exists and FALSE
   * otherwise.
   */
  public function hasTableByName( $name );

  /**
   * Lists the tables in the database.
   *
   * This method will cause the table schema to be loaded and cached for all
   * tables in this database.
   *
   * @return Table[] associative array of table name to Table objects.
   */
  public function listTables();

  /**
   * Queue the specified data definition for later application.
   *
   * This allows for mulitiple data definitions to be combined into a single
   * operation.
   *
   * @param DataDefinition $data_definition the data definition to queue.
   */
  protected function queueDataDefinition( DataDefinition $data_definition );

  /**
   * @see Connection::quoteChar()
   */
  public function quoteChar();

  /**
   * @see Connection::quoteName()
   */
  public function quoteName( $name );

  /**
   * @see Connection::quoteString()
   */
  public function quoteString( $value );

  /**
   * @see Connection::quoteTable()
   */
  public function quoteTable( $table );

}
