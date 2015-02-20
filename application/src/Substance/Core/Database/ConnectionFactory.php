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
use Substance\Core\Database\Alerts\DatabaseAlert;

/**
 * Connection factory, for getting connection instances.
 */
class ConnectionFactory {

  /**
   * @var string the active connection name.
   */
  protected static $active_connection_name = 'default';

  /**
   * @var Database[] active connections.
   */
  protected static $active_connections = array();

  /**
   * Hidden constructor - an instance of this class is not necessary.
   */
  protected function __construct() {
  }

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
        throw DatabaseAlert::database('No such database type for given name in database settings.')
          ->culprit( 'name', $connection_name )
          ->culprit( 'type', $type );
      }
    }
    // Return the active connection.
    return self::$active_connections[ $connection_name ][ $type ];
  }

  /**
   * Sets the active connection name, which will be used as the default
   * connection name for establishing new connections.
   *
   * @param string $name the connection name, or 'default' for the default.
   * @return self
   */
  public static function setActiveConnectionName( $name = 'default' ) {
    $this->active_connection_name = $name;
    return $this;
  }

}
