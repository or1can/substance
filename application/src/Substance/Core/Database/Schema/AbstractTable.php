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

namespace Substance\Core\Database\Schema;

use Substance\Core\Database\Connection;

/**
 * Abstract implementation of a schema Table.
 */
abstract class AbstractTable implements Table {

  /**
   * @var Connection the database connection we are working with.
   */
  protected $connection;

  /**
   * @var string the table name.
   */
  protected $name;

  /**
   * Construct a new table object to work with the specified table in the
   * connected database.
   *
   * @param Connection $connection the database to work with
   */
  public function __construct( Connection $connection ) {
    $this->connection = $connection;
  }

  public function __toString() {
    return 'TABLE<' . $this->name . '>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::getName()
   */
  public function getName() {
    return $this->name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::setName()
   */
  public function setName( $name ) {
    $this->name = $name;
  }

}
