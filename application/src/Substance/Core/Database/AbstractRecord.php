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
use Substance\Core\Database\SQL\Columns\AllColumns;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\FunctionExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\Schema\Types\Integer;

/**
 * An abstract implementation of Record, providing all the standard
 * functionality.
 *
 * A concrete implementation simply needs to extend this and provide the
 * implementation specific details.
 */
abstract class AbstractRecord implements Record {

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::all()
   */
  public static function all() {
    return self::select()->execute();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::backingStoreCreate()
   */
  public static function backingStoreCreate() {
    if ( !self::backingStoreExists() ) {
      $table = self::backingStoreTableName();
      $connection = ConnectionFactory::getConnection();
      $database = $connection->getDatabase();
      $table = $database->createTable( $table );
      // FIXME - This should add the records columns, not example ones!
      $table->addColumnByName( 'col1', new Integer() );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::backingStoreExists()
   */
  public static function backingStoreExists() {
    $table = self::backingStoreTableName();
    $connection = ConnectionFactory::getConnection();
    $database = $connection->getDatabase();
    return $database->hasTableByName( $table );
  }

  /**
   * Returns the name of the table in the backing store that is used to store
   * this objects information.
   */
  public static function backingStoreTableName() {
    $table = get_called_class();
    $table = strtr( $table, '\\', '_' );
    return $table;
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
  public static function find( array $conditions = array() ) {
    // Use SELECT query to find first matching record.
    $select = self::select();
    foreach ( $conditions as $column => $value ) {
      $select->where(
        new EqualsExpression(
          new ColumnNameExpression( $column ),
          new LiteralExpression( $value )
        )
      );
    }
    $select->limit( 1 );
    $results = $select->execute();
    // This is a little crude, but we want to return an instance of the current
    // class here.
    $result = $results->fetchObject( get_called_class() );
    return $result === FALSE ? NULL : $result;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::findAll()
   */
  public static function findAll( array $conditions = array() ) {
    // Use SELECT query to find all matching record.
    $select = self::select();
    foreach ( $conditions as $column => $value ) {
      $select->where(
        new EqualsExpression(
          new ColumnNameExpression( $column ),
          new LiteralExpression( $value )
        )
      );
    }
    // FIXME - The following statement does not know what kind of objects it
    // should be generating. We need to pass the calling class through to the
    // statement.
    return $select->execute();
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
    // Check backing store.
    self::backingStoreCreate();
    // Prepare SELECT query.
    $table = self::backingStoreTableName();
    return Select::select( $table )->addColumn( new AllColumns() );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::take()
   */
  public static function take() {
    // take() is a shorthand for find() with no conditions.
    return self::find();
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Record::takeSome()
   */
  public static function takeSome( $limit ) {
    $results = self::select()
      ->limit( $limit )
      ->execute();
    // FIXME - The following statement does not know what kind of objects it
    // should be generating. We need to pass the calling class through to the
    // statement.
    return $results;
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
