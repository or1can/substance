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

namespace Substance\Core\Database\MySQL;

use Substance\Core\Database\Database;

/**
 * Represents a database connection in Substance, which is an extension of the
 * core PHP PDO class.
 */
class Connection extends \Substance\Core\Database\Connection {

  public function __construct( &$options = array(), &$pdo_options = array() ) {
    // Set default MySQL options
    $pdo_options += array(
      // Use buffered queries for consistency with other drivers.
      \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
    );

    // Set default
    $options += array(
      Database::INIT_COMMANDS => array(),
    );
    $options[ Database::INIT_COMMANDS ] += array(
      'sql_mode' => "SET sql_mode = 'ANSI,STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER'",
      'names' => "SET NAMES utf8",
    );

    $dsn = array();
    $dsn[] = 'host=' . $options['host'];
    $dsn[] = 'port=' . $options['port'];
    $dsn[] = 'dbname=' . $options['database'];
    $dsn = 'mysql:' . implode( ';', $dsn );

    parent::__construct( $dsn, $options['username'], $options['password'], $options, $pdo_options );
  }

}
