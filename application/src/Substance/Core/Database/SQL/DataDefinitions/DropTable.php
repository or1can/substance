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

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\DataDefinition;

/**
 * Represents a DROP TABLE query.
 */
class DropTable extends DataDefinition {

  /**
   * @var string the table name to create.
   */
  protected $name;

  /**
   * Constructs a drop table object to drop a table with the specified
   * name.
   *
   * @param string $name the name of the table to create.
   */
  public function __construct( $name ) {
    $this->name = $name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Buildable::build()
   */
  public function build( Database $database ) {
    return $database->buildDropTable( $this );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\DataDefinition::check()
   */
  public function check() {
    // OK
  }

  /**
   * Returns the name of the table to drop.
   *
   * @return string the name of the table to drop.
   */
  public function getTableName() {
    return $this->name;
  }

}
