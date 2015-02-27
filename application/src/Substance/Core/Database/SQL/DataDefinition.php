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

namespace Substance\Core\Database\SQL;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Alerts\DatabaseAlert;
use Substance\Core\Database\Schema\Database;

/**
 * Represents a database definition.
 */
abstract class DataDefinition implements Buildable {

  /**
   * Apply this database definition to the underlying database.
   *
   * @param Database $database the database to apply this data definition to.
   * @throws Alert if something goes wrong.
   */
  public function apply( Database $database ) {
    $this->check();
    $sql = $this->build( $database );
    try {
      $database->getConnection()->execute( $sql );
    } catch ( \PDOException $pdoe ) {
      throw DatabaseAlert::database('Failed to apply database schema change')
        ->culprit( 'data definiton', $sql )
        ->culprit( 'PDO message', $pdoe->getMessage() );
    }
  }

  /**
   * Checks the data definition and throws an exception if it should not be
   * executed.
   *
   * @return void
   * @throws Alert if the data definition is no good.
   */
  abstract public function check();

}
