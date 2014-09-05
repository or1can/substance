<?php
/**
 * Database settings
 * =================
 *
 * Each database connection is specified as an array of settings, for example:
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
 * The only required element for a database connection is 'driverclass', which
 * defines the class that will handle the database connection. The other
 * elements of the connection are database specific, although the common
 * properties shared by all database drivers are:
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
 * prefix used for individual tables. The '*' element is mandatory in this case
 * and defines the default prefix for tables not explicitly specified. For
 * example, the following prefix specification would prefix the 'Users' table
 * to become 'shared_Users' and all other tables would have the 'mysite_'
 * prefix.
 * @code
 *   'prefix' => array(
 *     '*' => 'mysite_',
 *     'Users' => 'shared_',
 *   ),
 * @endcode
 *
 * The databases property is a multi-level array, with the top level mapping
 * database names to server types, which in turn map to the database connection
 * described above.
 *
 * The '*' database name is required, and will be used by default for all
 * queries. You can defined additional database connections with other names
 * and direct queries to them by name.
 *
 * Server types are either 'master' or 'slave', corresponding to normal master
 * slave setups. The 'master' slave type is required, as all queries can be
 * sent to the master. The 'slave' slave type is optional and if defined,
 * read-only queries may be automatically sent here.
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
 */
$databases = array(
  '*' => array(
    'master' => array(
      'driverclass' => 'You forgot to set the database connection driver class!',
    ),
  ),
);
