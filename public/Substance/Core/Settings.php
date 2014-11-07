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

namespace Substance\Core;

use Substance\Core\Database\Connection;
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
   * Returns the database settings array.
   *
   * Each database connection is specified as an array of settings, for
   * example:
   * @code
   * array(
   *   'driverclass' => 'Substance\Core\Database\MySQL\Connection',
   *   'database' => 'mydb',
   *   'username' => 'myuser',
   *   'password' => 'mypass',
   *   'host' => '127.0.0.1',
   *   'port' => '3306',
   *   'prefix' => NULL,
   * )
   * @endcode
   *
   * The only required element for a database connection is 'driverclass',
   * which defines the class that will handle the database connection. The
   * other elements of the connection are database specific, although the
   * common properties shared by all database drivers are:
   *
   * database - the database name, for drivers that support multiple databases
   * username - the username for authenticating access to the database
   * password - the password for authenticating access to the database
   * host - the server hosting the database
   * port - the port on the host the database is running on
   * prefix - a prefix that should be prepended to all tables (see below).
   *
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
   *
   * The databases property is a multi-level array, with the top level mapping
   * database names to server types, which in turn map to the database
   * connection described above.
   *
   * The '*' database name is required, and will be used by default for all
   * queries. You can defined additional database connections with other names
   * and direct queries to them by name.
   *
   * Server types are either 'master' or 'slave', corresponding to normal
   * master slave setups. The 'master' slave type is required, as all queries
   * can be sent to the master. The 'slave' slave type is optional and if
   * defined, read-only queries may be automatically sent here.
   *
   * The minimal database configuration example is:
   * @code
   * $databases = array(
   *   '*' => array(
   *     'master' => array(
   *       'driverclass' => 'Substance\Core\Database\MySQL\Connection',
   *       'database' => 'mydb',
   *       'username' => 'myuser',
   *       'password' => 'mypass',
   *       'host' => '127.0.0.1',
   *       'port' => '3306',
   *       'prefix' => NULL,
   *     ),
   *   ),
   * );
   * @endcode
   *
   * @return array a database settings array for the specified database name
   * and type.
   */
  public function getDatabaseSettings() {
    // TODO - Should we specify a name and type as arguments here?
    // @param string $name the database name, either '*' for or a more specific
    // name.
    // @param string $type the database type, either 'master' or 'slave'
    return array(
      '*' => array(
        'master' => array(
          'driverclass' => 'You forgot to set the database connection driver class!',
        ),
      ),
    );
  }

}
