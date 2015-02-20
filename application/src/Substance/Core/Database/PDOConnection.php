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
use Substance\Core\Database\Alerts\DatabaseAlert;

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
abstract class PDOConnection extends Connection {

  /**
   * @var \PDO the underlying PDO connection.
   */
  protected $pdo;

  /**
   * Construct a new MySQL database connection.
   *
   * @param string $dsn the data source name.
   * @param string $default_database the default database name.
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
  public function __construct( $dsn, $default_database, $username, $password, $prefix = '', $pdo_options = array()  ) {
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
    $this->pdo = new \PDO( $dsn, $username, $password, $pdo_options );

    // And now it's safe to call the parent constructor.
    parent::__construct( $default_database, $prefix );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::execute()
   */
  public function execute( $sql ) {
    $result = $this->pdo->exec( $sql );
    if ( $result === FALSE ) {
      $error_info = $this->pdo->errorInfo();
      throw DatabaseAlert::database( 'Failed to execute query' )
        ->culprit( 'query', $sql )
        ->culprit( 'error code', $this->pdo->errorCode() )
        ->culprit( 'driver code', $error_info[ 1 ] )
        ->culprit( 'driver message', $error_info[ 2 ] );
    } else {
      return $result;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::prepare()
   */
  public function prepare( $sql ) {
    try {
      return $this->pdo->prepare( $sql );
    } catch ( \Exception $ex ) {
      $error_info = $this->pdo->errorInfo();
      throw DatabaseAlert::database('Failed to prepare statement')
        ->culprit( 'query', $sql )
        ->culprit( 'error code', $this->pdo->errorCode() )
        ->culprit( 'driver code', $error_info[ 1 ] )
        ->culprit( 'driver message', $error_info[ 2 ] );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::query()
   */
  public function query( $sql ) {
    try {
      return $this->pdo->query( $sql );
    } catch ( \Exception $ex ) {
      $error_info = $this->pdo->errorInfo();
      throw DatabaseAlert::database('Failed to execute statement')
        ->culprit( 'query', $sql )
        ->culprit( 'error code', $this->pdo->errorCode() )
        ->culprit( 'driver code', $error_info[ 1 ] )
        ->culprit( 'driver message', $error_info[ 2 ] );
    }
  }

}
