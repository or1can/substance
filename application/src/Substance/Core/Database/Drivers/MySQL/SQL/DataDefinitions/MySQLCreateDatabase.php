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

namespace Substance\Core\Database\Drivers\MySQL\SQL\DataDefinitions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\DataDefinitions\CreateDatabase;

/**
 * Represents a CREATE DATABASE query.
 */
class MySQLCreateDatabase extends CreateDatabase {

  /**
   * Constructs a create database object to create a database with the
   * specified name.
   *
   * @param Database $database the database to operate on.
   * @param string $name the name of the database to create.
   */
  public function __construct( Database $database, $name ) {
    parent::__construct( $database, $name );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Definition::build()
   */
  public function build() {
    $sql = parent::build();
    $sql .= ' DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci';
    return $sql;
  }

}
