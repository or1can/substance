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

namespace Substance\Core\Database\Drivers\Unconnected;

use Substance\Core\Alert\Alerts\UnsupportedOperationAlert;
use Substance\Core\Database\Connection;

/**
 * An unconnected database connection object, which is not actually connected to
 * anything.
 *
 * Any operations requiring a physical database connection will fail.
 */
class UnconnectedConnection extends Connection {

  /**
   * @var UnconnectedConnection the single instance of this connection.
   */
  private static $instance;

  /**
   * Construct a new NULL database connection.
   */
  public function __construct() {
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::createDatabase()
   */
  public function createDatabase( $name ) {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected connections cannot create databases' )
      ->culprit( 'name', $name );
  }

  /**
   * Returns a unique instance of this unconnected connection.
   *
   * @return self
   */
  public static function getInstance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new UnconnectedConnection();
    }
    return self::$instance;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::listDatabases()
   */
  public function listDatabases() {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected connection have no databases to list' );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::loadDatabase()
   */
  protected function loadDatabase( $name ) {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected connection have no databases to load' )
      ->culprit( 'name', $name );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Connection::quoteChar()
   */
  public function quoteChar() {
    return '';
  }

}