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
use Substance\Core\Environment\Environment;

/**
 * Represents a database in Substance.
 */
abstract class Database extends \PDO {

  /**
   * @var string the database name.
   */
  protected $database_name;

  const DUMMY_CONNECTION = 'dummy_connection';

  const INIT_COMMANDS = 'init_commands';

  public function __construct( &$options = array(), &$pdo_options = array()  ) {
    // Force error exceptions, always.
    $options[ \PDO::ATTR_ERRMODE ] = \PDO::ERRMODE_EXCEPTION;

    // Set default options.
    $pdo_options += array(
      // Use our default statement class for all statements.
      \PDO::ATTR_STATEMENT_CLASS => array( '\Substance\Core\Database\Statement', array( $this ) ),
      // Emulate prepared statements until we know that we'll be running the
      // same statements *lots* of times.
      \PDO::ATTR_EMULATE_PREPARES => TRUE,
    );

    // Create the PDO connection
    parent::__construct( $options['dsn'], $options['username'], $options['password'], $pdo_options );

    // Execute init commands.
    if ( !empty( $options[ Database::INIT_COMMANDS ] ) ) {
      $this->exec( implode( ';', $options[ Database::INIT_COMMANDS ] ) );
    }

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
   * @param string $name the database name, either '*' for or a more specific
   * name.
   * @param string $type the database type, either 'master' or 'slave'.
   * @return Database the database connection for the specified name and
   * type.
   */
  public static function getConnection( $name = '*', $type = 'master' ) {
    $databases = Environment::getEnvironment()->getSettings()->getDatabaseSettings();
    if ( !array_key_exists( '*', $databases ) ) {
      throw Alert::alert('You must define connection name "*" in your database configuration' )
        ->culprit( 'defined connection names', implode( ',', array_keys( $databases ) ) );
    }
    if ( !array_key_exists( $name, $databases ) ) {
      throw Alert::alert('No such database name in database settings.' )
        ->culprit( 'name', $name );
    }
    $database = $databases[ $name ];
    if ( !array_key_exists( $type, $database ) ) {
      throw Alert::alert('No such database type for given name in database settings.' )
        ->culprit( 'name', $name )
        ->culprit( 'type', $type );
    }
    return Database::getConnectionFromSettings( $database[ $type ] );
  }

  public static function getConnectionFromSettings( $options = array() ) {
    $class = $options['driverclass'];
    return new $class( $options );
  }

  /**
   * Returns the database name for this connection. If the underlying database
   * does not allow access to multiple databases through a single connection,
   * this will return NULL.
   *
   * @return string the database name or NULL if not applicable
   */
  public function getDatabaseName() {
    return $this->database_name;
  }

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

  /**
   * Quote the specified table name for use in a query as an identifier.
   *
   * @param string $table the table name to quote
   * @return string the quoted table name, ready for use as an identifier
   */
  public function quoteChar() {
    return '"';
  }

  /**
   * Quote the specified name for use in a query as an identifier.
   *
   * @param string $name the name to quote
   * @return string the quoted name, ready for use as an identifier
   */
  public function quoteName( $name ) {
    $quote_char = $this->quoteChar();
    $double_quote_char = $quote_char . $quote_char;
    return $quote_char . str_replace( $quote_char, $double_quote_char, $name ) . $quote_char;
  }

  /**
   * Quote the specified value for use in a query as a string.
   *
   * @param string $value the value to quote.
   * @return string the quoted value, ready for use as a string.
   */
  public function quoteString( $value ) {
    $quote_char = "'";
    return $quote_char . str_replace( $quote_char, "\\$quote_char", $value ) . $quote_char;
  }

  /**
   * Quote the specified table name for use in a query as an identifier.
   *
   * @param string $table the table name to quote
   * @return string the quoted table name, ready for use as an identifier
   */
  public function quoteTable( $table ) {
    $quote_char = $this->quoteChar();
    $double_quote_char = $quote_char . $quote_char;
    return $quote_char . str_replace( $quote_char, $double_quote_char, $table ) . $quote_char;
  }

  /**
   * Sets the database name for this connection.
   *
   * @param string $name the database name.
   * @return self
   */
  protected function setDatabaseName( $name ) {
    $this->database_name = $name;
    return $this;
  }

}
