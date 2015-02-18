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

use Substance\Core\Alert\Alert;
use Substance\Core\Alert\Alerts\IllegalStateAlert;
use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\Schema\Column;
use Substance\Core\Database\SQL\DataDefinition;
use Substance\Core\Database\Schema\Table;

/**
 * Represents a CREATE TABLE query.
 */
class CreateTable extends DataDefinition {

  /**
   * @var Table the table to create.
   */
  protected $table;

  /**
   * @var boolean TRUE if this is a temporary table and FALSE otherwise.
   */
  protected $temporary;

  /**
   * Constructs a create table object to create a table with the specified
   * name.
   *
   * @param Database $database the database to create the table in.
   * @param Table $table the table to create.
   * @param boolean $temporary TRUE if this table is temporary and FALSE otherwise.
   */
  public function __construct( Database $database, Table $table, $temporary = FALSE ) {
    parent::__construct( $database );
    $this->table = $table;
    $this->temporary = $temporary;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Definition::build()
   */
  public function build() {
    $sql = 'CREATE';
    if ( $this->temporary ) {
      $sql .= ' TEMPORARY';
    }
    $sql .= ' TABLE ';
    $sql .= $this->database->quoteName( $this->table->getName() );
    $sql .= ' (';
    foreach ( $this->table->listColumns() as $column ) {
      $sql .= $this->database->quoteName( $column->getName() );
      $sql .= ' ';
      $sql .= $column->getType()->getName();
    }
    $sql .= ' )';
    return $sql;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\DataDefinition::check()
   */
  public function check() {
    if ( count( $this->table->listColumns() ) == 0 ) {
      throw IllegalStateAlert::illegalState( 'Cannot create a table without any columns' )
        ->culprit( 'table', $this->table->getName() );
    }
  }

}
