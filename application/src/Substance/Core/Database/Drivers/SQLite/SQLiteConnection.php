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

namespace Substance\Core\Database\Drivers\SQLite;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Connection;

/**
 * A SQLite database connection object, handling SQLite database connection
 * level functionality.
 */
class SQLiteConnection extends Connection {

  /**
   * Construct a new MySQL database connection.
   *
   * @param string $file the absolute path to the database file.
   * @param string|array $prefix a prefix that should be prepended to all
   * tables.
   * @param array $pdo_options an associative array of PDO driver options, keyed
   * by the PDO option with values appropriate to the option
   */
  public function __construct( $file, $prefix = '', $pdo_options = array() ) {
    $dsn = 'sqlite:' . $file;
    parent::__construct( $dsn, 'main', NULL, NULL, $prefix, $pdo_options );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::createDatabase()
   */
  public function createDatabase( $name ) {
    if ( $this->hasDatabaseByName( $name ) ) {
      throw Alert::alert( 'Database already exists', 'Cannot create new database with same name as an existing one' )
        ->culprit( 'database', $name );
    } else {
      // TODO
      return NULL;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::listDatabases()
   */
  public function listDatabases() {
    foreach ( $this->query('PRAGMA database_list') as $row ) {
      // Ignore any database with a name of "temp", as they are used for TEMP
      // objects and not actual databases.
      if ( $row->name != 'temp' ) {
        if ( !array_key_exists( $row->name, $this->databases ) ) {
          $this->databases[ $row->name ] = new SQLiteDatabase( $this, $row->name );
        }
      }
    }
    return $this->databases;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::loadDatabase()
   */
  protected function loadDatabase( $name ) {
    // TODO
    return NULL;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::quoteTable()
   */
  public function quoteTable( $table ) {
    $quote_char = $this->quoteChar();
    $double_quote_char = $quote_char . $quote_char;
    $parts = explode( '.', $table );
    foreach ( $parts as &$part ) {
      $part = $quote_char . str_replace( $quote_char, $double_quote_char, $part ) . $quote_char;
    }
    return implode( '.', $parts );
  }

}
