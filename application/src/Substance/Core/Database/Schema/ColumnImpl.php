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

namespace Substance\Core\Database\Schema;

use Substance\Core\Alert\Alerts\UnsupportedOperationAlert;
use Substance\Core\Alert\Culprit;
/**
 * A basic implementation of a column.
 */
class ColumnImpl implements Column {

  /**
   * @var boolean TRUE if the column allows NULL values and FALSE otherwise.
   */
  protected $allows_null;

  /**
   * @var mixed the columns default value.
   */
  protected $default;

  /**
   * @var string the columns name.
   */
  protected $name;

  /**
   * @var Table the columns parent table.
   */
  protected $table;

  /**
   * @var Type the columns type.
   */
  protected $type;

  /**
   * Constructs a new column.
   *
   * @param Table $table the columns parent table.
   * @param string $name the column name.
   * @param Type $type the columns type.
   * @param mixed $default the columns default value.
   * @param boolean $allows_null TRUE if the column allows NULL values and FALSE otherwise.
   */
  public function __construct( Table $table, $name, Type $type, $default = NULL, $allows_null = TRUE ) {
    $this->allows_null = $allows_null;
    $this->default = $default;
    $this->name = $name;
    $this->table = $table;
    $this->type = $type;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::allowsNull()
   */
  public function allowsNull() {
    return $this->allows_null;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::drop()
   */
  public function drop() {
    throw UnsupportedOperationAlert::alert('Cannot currently drop columns')
      ->culprit( 'column', $this );
    return $this;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::getDefault()
   */
  public function getDefault() {
    return $this->default;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::getName()
   */
  public function getName() {
    return $this->name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::getTable()
   */
  public function getTable() {
    return $this->table;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::getType()
   */
  public function getType() {
    return $this->type;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::setDefault()
   */
  public function setDefault( $value ) {
    // FIXME - This is a schema change.
    throw UnsupportedOperationAlert::alert('Cannot currently change column default')
      ->culprit( 'column', $this )
      ->Culprit( 'default', $value );
    $this->default = $value;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::setName()
   */
  public function setName( $name ) {
    // FIXME - This is a schema change.
    throw UnsupportedOperationAlert::alert('Cannot currently change column name')
      ->culprit( 'column', $this )
      ->culprit( 'name', $name );
    $this->name = $name;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Column::setType()
   */
  public function setType( Type $type ) {
    // FIXME - This is a schema change.
    throw UnsupportedOperationAlert::alert('Cannot currently change column type')
      ->culprit( 'column', $this )
      ->culprit( 'type', $type );
    $this->type = $type;
  }

}
