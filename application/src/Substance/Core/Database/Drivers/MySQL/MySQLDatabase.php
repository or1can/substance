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

namespace Substance\Core\Database\Drivers\MySQL;

use Substance\Core\Database\Schema\AbstractDatabase;
use Substance\Core\Database\Schema\BasicTable;
use Substance\Core\Database\Schema\Size;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\SQL\Columns\AllColumns;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * A MySQL database schema object, handling MySQL database level functionality.
 */
class MySQLDatabase extends AbstractDatabase {

  public function buildInteger( Integer $integer ) {
    switch ( $integer->getSize()->getValue() ) {
      case Size::TINY:
        return 'TINYINT';
        break;
      case Size::SMALL:
        return 'SMALLINT';
        break;
      case Size::MEDIUM:
        return 'MEDIUMINT';
        break;
      case Size::NORMAL:
        return 'INTEGER';
        break;
      case Size::BIG:
        return 'BIGINT';
        break;
      default:
        throw IllegalValueAlert::illegal_value('Only tiny, small, medium, normal and big sizes are supported')
        ->culprit( 'size', $integer->getSize() );
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Database::listTables()
   */
  public function listTables() {
    $select = Select::select('information_schema.TABLES')
      ->addColumn( new AllColumns() )
      ->where( new EqualsExpression( new ColumnNameExpression('TABLE_SCHEMA'), new LiteralExpression( $this->getName() ) ) );
    $statement = $this->execute( $select );
    foreach ( $statement as $row ) {
      if ( !array_key_exists( $row->TABLE_NAME, $this->tables ) ) {
        $this->tables[ $row->TABLE_NAME ] = new BasicTable( $this, $row->TABLE_NAME );
      }
    }
    return $this->tables;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\AbstractDatabase::loadTable()
   */
  protected function loadTable( $name ) {
    $select = Select::select('information_schema.TABLES')
      ->addColumnByName('TABLE_NAME')
      ->where( new EqualsExpression( new ColumnNameExpression('TABLE_SCHEMA'), new LiteralExpression( $this->name ) ) )
      ->where( new EqualsExpression( new ColumnNameExpression('TABLE_NAME'), new LiteralExpression( $name ) ) );
    $statement = $this->execute( $select );
    if ( $statement->rowCount() == 1 ) {
      $record = $statement->fetchObject();
      return new BasicTable( $this, $record->TABLE_NAME );
    } else {
      return NULL;
    }
  }

}
