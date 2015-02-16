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
use Substance\Core\Database\SQL\DataDefinitions\DropTable;
use Substance\Core\Database\SQL\Query;

/**
 * An abstract database schema implementation.
 */
abstract class AbstractDatabase implements Database {

  /**
   * @var Connection the underlying database connection
   */
  protected $connection;

  /**
   * @var string the database name.
   */
  protected $name;

  /**
   * @var Table[] this databases tables.
   */
  protected $tables = array();

  /**
   * Construct a new database object.
   *
   * @param Connection $connection the database connection.
   * @param string $name the database name.
   */
  public function __construct( Connection $connection, $name ) {
    $this->connection = $connection;
    $this->name = $name;
  }

  public function __toString() {
    return 'DATABASE<' . $this->name . '>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Database::dropTable()
   */
  public function dropTable( Table $table ) {
    $drop = new DropTable( $this, $table->getName() );
    $this->connection->exec( $drop->build() );
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::execute()
   */
  public function execute( Query $query ) {
    $sql = $query->build( $this );
    $statement = $this->connection->prepare( $sql );
    $result = $statement->execute( $query->getArguments() );
    if ( $result === FALSE ) {
      $error_info = $statement->errorInfo();
      throw Alert::alert( 'Failed to execute query' )
        ->culprit( 'query', $query )
        ->culprit( 'error code', $statement->errorCode() )
        ->culprit( 'driver code', $error_info[ 1 ] )
        ->culprit( 'driver message', $error_info[ 2 ] );
    }
    return $statement;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::getName()
   */
  public function getName() {
    return $this->name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::getTable()
   */
  public function getTable( $name ) {
    if ( $this->hasTableByName( $name ) ) {
      return $this->tables[ $name ];
    } else {
      throw Alert::alert( 'No such table', 'There is no table with the specified name.' )
        ->culprit( 'name', $name )
        ->culprit( 'database', $this->getName() );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::hasTableByName()
   */
  public function hasTableByName( $name ) {
    if ( array_key_exists( $name, $this->tables ) ) {
      return TRUE;
    } else {
      // The table schema may not have been loaded yet...
      $table = $this->loadTable( $name );
      if ( is_null( $table ) ) {
        return FALSE;
      } else {
        $this->tables[ $name ] = $table;
        return TRUE;
      }
    }
  }

  /**
   * Load the specified table schema from the underlying database.
   *
   * @param string $name the name of the table to load.
   * @return Table the loaded table or NULL if no table exists.
   */
  abstract protected function loadTable( $name );

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteChar()
   */
  public function quoteChar() {
    return $this->connection->quoteChar();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteName()
   */
  public function quoteName( $name ) {
    return $this->connection->quoteName( $name );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteString()
   */
  public function quoteString( $value ) {
    return $this->connection->quoteString( $value );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteTable()
   */
  public function quoteTable( $table ) {
    return $this->connection->quoteTable( $table );
  }

}
