<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2015 Kevin Rogers
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
use Substance\Core\Database\Schema\AbstractDatabase;

/**
 * An unconnected database object, which is not actually connected to anything.
 *
 * Any operations requiring a physical database connection will fail.
 */
class UnconnectedDatabase extends AbstractDatabase {

  /**
   * @var UnconnectedConnection the single instance of this connection.
   */
  private static $instance;

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Database::createTable()
   */
  public function createTable( $name ) {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected databases cannot create tables' )
      ->culprit( 'name', $name );
  }

  /**
   * Returns a unique instance of this database.
   *
   * @return self
   */
  public static function getInstance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new UnconnectedDatabase('unconnected');
    }
    return self::$instance;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Database::listTables()
   */
  public function listTables() {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected databases have no tables to list' );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\AbstractDatabase::loadTable()
   */
  protected function loadTable( $name ) {
    throw UnsupportedOperationAlert::unsupportedOperation( 'Unconnected databases have no tables to load' );
  }

}
