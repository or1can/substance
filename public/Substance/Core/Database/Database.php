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

use Substance\Core\Environment\Environment;

/**
 * Represents a database in Substance.
 */
class Database {

  const DUMMY_CONNECTION = 'dummy_connection';

  const INIT_COMMANDS = 'init_commands';

  public static function getConnectionFromSettings( $options = array() ) {
    $class = $options['driverclass'];
    return new $class( $options );
  }

  /**
   * @param string $name the database name, either '*' for or a more specific
   * name.
   * @param string $type the database type, either 'master' or 'slave'.
   * @return Connection the database connection for the specified name and
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

}
