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

namespace Substance\Core\Database;

use Substance\Core\Presentation\Elements\Select;

/**
 * Represents a database table record in Substance, similar in principle to a
 * Rails ActiveRecord.
 */
interface Record {

  /**
   * Returns a PDO statement allowing you to iterate through all records.
   *
   * Shorthand for:
   *
   * Record->select()->execute()
   *
   * @return Statement
   */
  public static function all();

  /**
   * Creates the backing store for this record, if it does not already exist.
   */
  public static function backingStoreCreate();

  /**
   * Checks if the backing store for this record exists.
   *
   * @return bool TRUE if the backing store exists and FALSE otherwise.
   */
  public static function backingStoreExists();

  /**
   * Creates a new Record object, saves it to the database then returns it.
   *
   * @return self
  */
  public static function create();

  /**
   * Constructs a new Record object and returns it without saving to the
   * database.
   *
   * @return self
  */
  public static function construct();

  /**
   * Deletes this record from the database.
   */
  public function delete();

  /**
   * Deletes all records of this type from the database.
   */
  public function deleteAll();

  /**
   * Returns the first record with specified conditions.
   *
   * SELECT * FROM table WHERE ($conditions match) LIMIT 1
   *
   * @param array $conditions associative array of column-value pairs for the
   * record to find.
   * @return self an instance of self for the found record, or NULL if no record
   * is found.
   */
  public static function find( array $conditions = array() );

  /**
   * Returns a Statment for all records matching the specified conditions.
   *
   * SELECT * FROM table WHERE ($conditions match)
   *
   * @param array $conditions associative array of column-value pairs for the
   * records to find.
   * @return Statement
   */
  public static function findAll( array $conditions = array() );

  /**
   * Returns the record with the specified primary key.
   *
   * SELECT * FROM table WHERE table.primary_key = $id
   *
   * @param unknown $id Primary key of record to find.
   * @return self
   */
  public function findByPrimaryKey( $id );

  /**
   * Returns the first record ordered by primary key.
   *
   * SELECT * FROM table ORDER BY table.primary_key ASC LIMIT 1
   *
   * Shorthand for:
   *
   * Record->firstFew( 1 )
   *
   * @return self
   */
  public function first();

  /**
   * Returns the required number of records ordered by primary key in
   * ascending order.
   *
   * SELECT * FROM table ORDER BY table.primary_key ASC LIMIT $limit
   *
   * Shorthand for:
   *
   * Record->select()->orderBy( 'primary_key', 'ASC' )->limit( $limit )->execute()
   *
   * @return Statement
   */
  public function firstFew( $limit );

  /**
   * Returns the last record ordered by descending primary key.
   *
   * SELECT * FROM table ORDER BY table.primary_key DESC LIMIT 1
   *
   * Shorthand for:
   *
   * Record->lastFew( 1 )
   *
   * @return self
   */
  public function last();

  /**
   * Returns the required number of records ordered by primary key in
   * descending order.
   *
   * SELECT * FROM table ORDER BY table.primary_key DESC LIMIT $limit
   *
   * Shorthand for:
   *
   * Record->select()->orderBy( 'primary_key', 'DESC' )->limit( $limit )->execute()
   *
   * @return Statement
   */
  public function lastFew( $limit );

  /**
   * Returns a Select object to query this Record.
   *
   * @return Select
   */
  public static function select();

  /**
   * Returns the first record without any implicit ordering.
   *
   * SELECT * FROM table LIMIT 1
   *
   * Shorthand for:
   *
   * Record->select()->limit( 1 )->execute()
   *
   * @return self
   */
  public static function take();

  /**
   * Returns the required number of record without any implicit ordering.
   *
   * SELECT * FROM table LIMIT $limit
   *
   * Shorthand for:
   *
   * Record->select()->limit( $limit )->execute()
   *
   * @return Statement
   */
  public static function takeSome( $limit );

  /**
   * Updates this record, saving changes to the database.
   */
  public function update();

  /**
   * Updates all records of this type in the database.
   */
  public static function updateAll();

}
