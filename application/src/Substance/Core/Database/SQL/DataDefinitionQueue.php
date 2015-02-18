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

namespace Substance\Core\Database\SQL;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Schema\Database;

/**
 * Represents a queue of database definitions.
 */
class DataDefinitionQueue {

  /**
   * @var array the queue of database definitions.
   */
  protected $queue = array();

  /**
   * Constructs a new data definition operating on the specified database.
   */
  public function __construct() {
  }

  /**
   * Apply the data definitions in this queue.
   */
  public function apply() {
    while ( $data_definition = array_shift( $this->queue ) ) {
      $data_definition->apply();
    }
  }

  /**
   * Push the specified data definition on to the end of the queue.
   *
   * @param DataDefinition $data_definition the item to add.
   */
  public function push( DataDefinition $data_definition ) {
    $this->queue[] = $data_definition;
  }

}
