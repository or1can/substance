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

namespace Substance\Core\Database\Drivers\MySQL;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Columns\AllColumns;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Represents a database connection in Substance, which is an extension of the
 * core PHP PDO class.
 */
class MySQLDatabase extends Database {

  /**
   * Construct a new MySQL database connection.
   *
   * @param string $host the server host name or IP address.
   * @param string $database the database name
   * @param string $username the database username
   * @param string $password the database password
   * @param number $port the port the database server is running on
   * @param string|array $prefix a prefix that should be prepended to all
   * tables.
   * @param array $pdo_options an associative array of PDO driver options, keyed
   * by the PDO option with values appropriate to the option
   */
  public function __construct( $host, $database, $username, $password, $port = 3306, $prefix = '', $pdo_options = array() ) {
    // Set default MySQL options
    $pdo_options += array(
      // Use buffered queries for consistency with other drivers.
      \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
    );

    $dsn = array();
    $dsn[] = 'host=' . $host;
    $dsn[] = 'port=' . $port;
    $dsn[] = 'dbname=' . $database;
    $dsn = 'mysql:' . implode( ';', $dsn );

    parent::__construct( $dsn, $username, $password, $prefix, $pdo_options );

    $this->setDatabaseName( $database );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::createDatabases()
   */
  public function createDatabases( $name ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::createTable()
   */
  public function createTable( $name ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::initaliseConnection()
   */
  public function initaliseConnection() {
    $this->connection->exec( "SET sql_mode = 'ANSI,STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER'" );
    $this->connection->exec( "SET NAMES utf8" );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::listDatabases()
   */
  public function listDatabases() {
    $databases = array();
    // We would use SHOW DATABASES LIKE or SHOW DATABASES WHERE here, but for
    // some reason, $this->query() returns false for either, so we must do the
    // same in PHP instead.
    foreach ( $this->connection->query('SHOW DATABASES') as $row ) {
      if ( $row->Database != 'information_schema' ) {
        if ( $row->Database === $this->getDatabaseName() ) {
          $databases[ $row->Database ] = $this;
        } else {
          // TODO - We need to associate the database name with a Database
          // instance, but I'd rather create them lazily if possible to avoid
          // the overhead of establishing a database connection unless it is
          // necessary.
          $databases[ $row->Database ] = NULL;
        }
      }
    }
    return $databases;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::listTables()
   */
  public function listTables() {
    $select = Select::select('information_schema.TABLES')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('TABLE_SCHEMA'), new LiteralExpression( $this->getDatabaseName() ) ) );
    $statement = $this->execute( $select );
    $tables = array();
    foreach ( $statement as $row ) {
      $tables[ $row->TABLE_NAME ] = new MySQLTable( $this, $row );
    }
    return $tables;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteChar()
   */
  public function quoteChar() {
    return '`';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::quoteTable()
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
