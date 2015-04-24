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

namespace Substance\Core\Database;

use Substance\Core\Alert\Alerts\UnsupportedOperationAlert;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Represents a database statement in Substance, which is an extension of the
 * core PHP PDOStatement class.
 */
abstract class AbstractRecord implements Record {

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::all()
   */
  public function all() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::create()
   */
  public static function create() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::construct()
   */
  public static function construct() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::delete()
   */
  public function delete() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::deleteAll()
   */
  public function deleteAll() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::find()
   */
  public function find( array $conditions ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::findAll()
   */
  public function findAll( array $conditions ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::findByPrimaryKey()
   */
  public function findByPrimaryKey( $id ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::first()
   */
  public function first() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::firstFew()
   */
  public function firstFew( $limit ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::last()
   */
  public function last() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::lastFew()
   */
  public function lastFew( $limit ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::select()
   */
  public static function select() {
    $table = get_called_class();
    $table = strtr( $table, '\\', '_' );
    return Select::select( $table );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::take()
   */
  public function take() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::takeSome()
   */
  public function takeSome( $limit ) {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::update()
   */
  public function update() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::updateAll()
   */
  public static function updateAll() {
    // FIXME
    throw UnsupportedOperationAlert::unsupportedOperation();
  }

}
