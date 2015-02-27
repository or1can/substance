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
use Substance\Core\Database\Schema\Table;
use Substance\Core\Database\SQL\DataDefinition;

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
   * @param Table $table the table to create.
   * @param boolean $temporary TRUE if this table is temporary and FALSE otherwise.
   */
  public function __construct( Table $table, $temporary = FALSE ) {
    $this->table = $table;
    $this->temporary = $temporary;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Buildable::build()
   */
  public function build( Database $database ) {
    return $database->buildCreateTable( $this );
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

  /**
   * Returns the table to create.
   *
   * @return Table the table to create.
   */
  public function getTable() {
    return $this->table;
  }

  /**
   * Checks if this is a temporary table.
   *
   * @return boolean TRUE if this is a temporary table and FALSE otherwise.
   */
  public function isTemporary() {
    return $this->temporary;
  }

}
