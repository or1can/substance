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

use Substance\Core\Database\Schema\Database;

/**
 * Basic implementation of a schema Table.
 */
class BasicTable implements Table {

  /**
   * @var Column[] this tables columns.
   */
  protected $columns = array();

  /**
   * @var Database the database we are working with.
   */
  protected $database;

  /**
   * @var Index[] this tables indexes.
   */
  protected $indexes = array();

  /**
   * @var string the table name.
   */
  protected $name;

  /**
   * Construct a new table object to work with the specified table in the
   * database.
   *
   * @param Database $database the database to work with
   * @param string $name the table name.
   */
  public function __construct( Database $database, $name ) {
    $this->database = $database;
    $this->name = $name;
  }

  public function __toString() {
    return 'TABLE<' . $this->name . '>';
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::addColumn()
   */
  public function addColumn( Column $column ) {
    if ( $this->hasColumnByName( $column->getName() ) ) {
      throw Alert::alert( 'Column already exists', 'Each column must have a unique name.' )
        ->culprit( 'name', $column->getName() )
        ->culprit( 'existing column', $this->columns[ $column->getName() ] )
        ->culprit( 'duplicate column', $column );
    } else {
      $this->columns[ $column->getName() ] = $column;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::addIndex()
   */
  public function addIndex( Index $index ) {
    if ( $this->hasIndexByName( $index->getName() ) ) {
      throw Alert::alert( 'Index already exists', 'Each index must have a unique name.' )
        ->culprit( 'name', $index->getName() )
        ->culprit( 'existing index', $this->indexes[ $index->getName() ] )
        ->culprit( 'duplicate index', $index );
    } else {
      $this->indexes[ $index->getName() ] = $index;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::drop()
   */
  public function drop() {
    $this->database->dropTable( $this );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::getColumn()
   */
  public function getColumn( $name ) {
    if ( $this->hasColumnByName( $name ) ) {
      throw Alert::alert( 'No such column', 'There is no column with the specified name.' )
        ->culprit( 'name', $name )
        ->culprit( 'table', $this->getName() );
    } else {
      return $this->columns[ $name ];
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::getIndex()
   */
  public function getIndex( $name ) {
    if ( $this->hasIndexByName( $name ) ) {
      throw Alert::alert( 'No such index', 'There is no index with the specified name.' )
        ->culprit( 'name', $name )
        ->culprit( 'table', $this->getName() );
    } else {
      return $this->indexes[ $name ];
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::getName()
   */
  public function getName() {
    return $this->name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::hasColumnByName()
   */
  public function hasColumnByName( $name ) {
    return array_key_exists( $name, $this->columns );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::hasIndexByName()
   */
  public function hasIndexByName( $name ) {
    return array_key_exists( $name, $this->indexes );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::listColumns()
   */
  public function listColumns() {
    return $this->columns;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::listIndexes()
   */
  public function listIndexes() {
    return $this->indexes;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Table::setName()
   */
  public function setName( $name ) {
    $this->name = $name;
  }

}
