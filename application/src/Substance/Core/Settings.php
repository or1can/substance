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

namespace Substance\Core;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Database;
use Substance\Core\Environment\Environment;

/**
 * This class encapsulates Substance application settings, for example database
 * connection details.
 *
 * The distinction between an Environment and Settings is that the Environment
 * describes where the application is running, while the Settings configure the
 * application. Hence an application can run in different Environments with the
 * same settings.
 *
 * @see Environment
 */
abstract class Settings {

  /**
   * Returns the master database connection for the specified connection name.
   *
   * This method must be overridden in all settings.
   *
   * If you have multiple masters, simply return a connection to a random one.
   *
   * @param string $name the connection name.
   * @return Database the master database connection.
   */
  public function getDatabaseMaster( $name = 'default' ) {
    throw Alert::alert(
      'No database connection set',
      'You must set a master database connection in your setings.'
    );
  }

  /**
   * Returns the slave database connection for the specified connection name.
   *
   * Override this method if you want Substance to use your slave database for
   * read only queries. Queries will use the master database by default and must
   * be marked to use a slave database.
   *
   * If you have multiple slaves, simply return a connection to a random one.
   *
   * @param string $name the connection name.
   * @return Database the slave database connection.
   */
  public function getDatabaseSlave( $name = 'default' ) {
    return $this->getDatabaseMaster( $name );
  }

}
