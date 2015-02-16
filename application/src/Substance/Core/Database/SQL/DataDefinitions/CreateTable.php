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

/**
 * Represents a CREATE TABLE query.
 */
class CreateTable extends DataDefinition {

  /**
   * @var Column[] the columns for this table.
   */
  protected $columns = array();

  /**
   * @var string the table name to create.
   */
  protected $name;

  /**
   * @var boolean TRUE if this is a temporary table and FALSE otherwise.
   */
  protected $temporary;

  /**
   * Constructs a create table object to create a table with the specified
   * name.
   *
   * @param Database $database the database to create the table in.
   * @param string $name the name of the table to create.
   * @param boolean $temporary TRUE if this table is temporary and FALSE otherwise.
   */
  public function __construct( Database $database, $name, $temporary = FALSE ) {
    parent::__construct( $database );
    $this->name = $name;
    $this->temporary = $temporary;
  }

  /**
   * Adds the specified column to this table.
   *
   * @param Column $column the column to add.
   */
  public function addColumn( Column $column ) {
    if ( array_key_exists( $column->getName(), $this->columns ) ) {
      throw Alert::alert( 'Column already exists', 'Each column must have a unique name.' )
        ->culprit( 'name', $column->getName() )
        ->culprit( 'existing column', $this->columns[ $column->getName() ] )
        ->culprit( 'duplicate column', $column );
    } else {
      $this->columns[ $column->getName() ] = $column;
    }
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
    $sql .= $this->database->quoteName( $this->name );
    $sql .= ' (';
    foreach ( $this->columns as $column ) {
      $sql .= $column->getName();
    }
    $sql .= ' )';
    return $sql;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\DataDefinition::check()
   */
  public function check() {
    if ( count( $this->columns ) == 0 ) {
      throw IllegalStateAlert::illegalState( 'Cannot create a table without any columns' )
      ->culprit( 'table', $this->name );
    }
  }

}
