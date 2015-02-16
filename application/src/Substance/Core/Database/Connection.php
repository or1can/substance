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

use Substance\Core\Alert\Alert;
use Substance\Core\Environment\Environment;

/**
 * Represents a database connection.
 *
 * Each concrete database implementation can define its own options, with the
 * core options and behaviours defined here.
 *
 * A database connection can optionally prefix one or more table names with a
 * given value. Prefixing behaviour is controlled by the prefix property to the
 * database constructor.
 * The prefix property can be either a simple string value to be prepended to
 * every table, e.g.
 * @code
 *   $prefix = 'prefix_';
 * @endcode
 * or it can be an array of table names and string prefixes to control the
 * prefix used for individual tables. Using a '*' as the table name defines the
 * default prefix for tables not explicitly specified. For example, the
 * following prefix specification would prefix the 'Users' table to become
 * 'shared_Users' and all other tables would have the 'mysite_' prefix.
 * @code
 *   'prefix' => array(
 *     '*' => 'mysite_',
 *     'Users' => 'shared_',
 *   ),
 * @endcode
 */
abstract class Connection extends \PDO {

  /**
   * @var string the active connection name.
   */
  protected static $active_connection_name = 'default';

  /**
   * @var Database[] active connections.
   */
  protected static $active_connections = array();

  /**
   * @var Database[] this connections databases.
   */
  protected $databases = array();

  /**
   * @var string the connection name.
   */
  protected $name;

  /**
   * Construct a new MySQL database connection.
   *
   * @param string $dsn the data source name.
   * @param string $username the database username
   * @param string $password the database password
   * @param string|array $prefix a prefix that should be prepended to all
   * tables. A string value will be prepended to all tables, while an array
   * specifies prefixes for specific tables.
   * The prefix property can be either a simple string value to be prepended to
   * every table, e.g.
   * @code
   *   'prefix' => 'prefix_',
   * @endcode
   * or it can be an array of table names and string prefixes to control the
   * prefix used for individual tables. The '*' element is mandatory in this
   * case and defines the default prefix for tables not explicitly specified.
   * For example, the following prefix specification would prefix the 'Users'
   * table to become 'shared_Users' and all other tables would have the
   * 'mysite_' prefix.
   * @code
   *   'prefix' => array(
   *     '*' => 'mysite_',
   *     'Users' => 'shared_',
   *   ),
   * @endcode
   * @param array $pdo_options an associative array of PDO driver options, keyed
   * by the PDO option with values appropriate to the option
   */
  public function __construct( $dsn, $username, $password, $prefix = '', $pdo_options = array()  ) {
    // Force error exceptions, always.
    $pdo_options[ \PDO::ATTR_ERRMODE ] = \PDO::ERRMODE_EXCEPTION;

    // Set default options.
    $pdo_options += array(
      // Use our default statement class for all statements.
      \PDO::ATTR_STATEMENT_CLASS => array( '\Substance\Core\Database\Statement', array( $this ) ),
      // Emulate prepared statements until we know that we'll be running the
      // same statements *lots* of times.
      \PDO::ATTR_EMULATE_PREPARES => TRUE,
    );

    // Create the PDO connection
    parent::__construct( $dsn, $username, $password, $pdo_options );

    // Execute init commands.
    $this->initaliseConnection();
  }

  public function __toString() {
    return 'CONNECTION<' . $this->name . '>';
  }

  /**
   * Creates a database with the specified name in the server specified in this
   * Schema's connection.
   *
   * @param string $name the new database name.
   * @return Database the new database.
   */
  abstract public function createDatabase( $name );

  /**
   * Returns the active connection name, that is being used as the default
   * connection name for establishing new connections.
   *
   * @return string the active connection name, or 'default' for default
   * connection.
   */
  public static function getActiveConnectionName() {
    return $this->active_connection_name;
  }

  /**
   * Returns a database connection for the current active connection, or the
   * specified override.
   *
   * @param string $type the database type, either 'master' or 'slave'.
   * @param string $name the connection name to use instead of the active
   * connection, or NULL to use the active connection.
   * @return Connection the database connection for the specified name and
   * type.
   */
  public static function getConnection( $type = 'master', $name = NULL ) {
    // Use the active connection by default, but override with a supplied one.
    $connection_name = isset( $name ) ? $name : self::$active_connection_name;
    // Set a connection in the cache, if required.
    if ( !isset( self::$active_connections[ $connection_name ][ $type ] ) ) {
      $settings = Environment::getEnvironment()->getSettings();
      switch ( $type ) {
        case 'master':
          self::$active_connections[ $connection_name ][ $type ] = $settings->getDatabaseMaster( $connection_name );
          break;
        case 'slave':
          self::$active_connections[ $connection_name ][ $type ] = $settings->getDatabaseSlave( $connection_name );
          break;
        default:
          throw Alert::alert( 'Unsupported database type', 'Database type must be either "master" or "slave"' )
          ->culprit( 'type', $type );
          break;
      }
      if ( !isset( self::$active_connections[ $connection_name ][ $type ] ) ) {
        throw Alert::alert( 'No such database type for given name in database settings.' )
          ->culprit( 'name', $connection_name )
          ->culprit( 'type', $type );
      }
    }
    // Return the active connection.
    return self::$active_connections[ $connection_name ][ $type ];
  }

  /**
   * Returns the database object for the database with the specified name.
   *
   * @param string $name the database name
   * @return Database the database object for the specified database.
   */
  public function getDatabase( $name ) {
    if ( $this->hasDatabaseByName( $name ) ) {
      return $this->databases[ $name ];
    } else {
      throw Alert::alert( 'No such database', 'There is no database with the specified name.' )
        ->culprit( 'name', $name );
    }
  }

  /**
   * Checks if the specified database exists.
   *
   * @param string $name the database name to check.
   * @return boolean TRUE if the specified database exists and FALSE otherwise.
   */
  public function hasDatabaseByName( $name ) {
    if ( array_key_exists( $name, $this->databases ) ) {
      return TRUE;
    } else {
      // The database schema may not have been loaded yet...
      $database = $this->loadDatabase( $name );
      if ( is_null( $database ) ) {
        return FALSE;
      } else {
        $this->database[ $name ] = $database;
        return TRUE;
      }
    }
  }

  /**
   * This method is called just after the connection has been made and is used
   * to allow concrete instances to fine tune the connection.
   */
  public function initaliseConnection() {
  }

  /**
   * Lists the available databases on the server that this connections user has
   * access to.
   *
   * @return Database[] associative array of database name to Database objects.
   */
  abstract public function listDatabases();

  /**
   * Load the specified database schema from the underlying database.
   *
   * @param string $name the name of the database to load.
   * @return Table the loaded database or NULL if no database exists.
   */
  abstract protected function loadDatabase( $name );

  /**
   * Returns the quote character for quoting identifiers.
   *
   * @return string the quot character for quoting identifiers.
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
   * Sets the active connection name, which will be used as the default
   * connection name for establishing new connections.
   *
   * @param string $name the connection name, or 'default' for the default.
   * @return self
   */
  public static function setActiveConnectionName( $name = NULL ) {
    $this->active_connection_name = $name;
    return $this;
  }

  /**
   * Sets the connection name.
   *
   * This is only really used for debugging.
   *
   * @param string $name the connection name.
   */
  protected function setName( $name ) {
    $this->name = $name;
  }

}
