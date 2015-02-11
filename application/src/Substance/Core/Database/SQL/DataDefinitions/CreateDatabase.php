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

namespace Substance\Core\Database\SQL\DataDefinitions;

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\DataDefinition;

/**
 * Represents a CREATE DATABASE query.
 */
class CreateDatabase extends DataDefinition {

  /**
   * @var string the database name to create.
   */
  protected $name;

  /**
   * Constructs a create database object to create a database with the
   * specified name.
   *
   * Although it seems odd, we do need a database to operate on here, even
   * though we're creating a database.
   *
   * @param Database $database the database to operate on.
   * @param string $name the name of the database to create.
   */
  public function __construct( Database $database, $name ) {
    parent::__construct( $database );
    $this->name = $name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Definition::build()
   */
  public function build() {
    $sql = 'CREATE DATABASE ';
    $sql .= $this->database->quoteName( $this->name );
    return $sql;
  }

}
