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

namespace Substance\Core\Database\Drivers\SQLite;

use Substance\Core\Database\Schema\AbstractDatabase;
use Substance\Core\Database\Schema\BasicTable;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * A SQLite database schema object, handling SQLite database level
 * functionality.
 */
class SQLiteDatabase extends AbstractDatabase {

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Database::listTables()
   */
  public function listTables() {
    // SQLite stores schema information in a hidden sqlite_master table in each
    // database. The first database in a connection has the name "main".
    $select = Select::select( $this->getName() . '.sqlite_master' )
      ->addColumn( new ColumnNameExpression('name') )
      ->where( new EqualsExpression( new ColumnNameExpression('type'), new ColumnNameExpression('table') ) );
    $statement = $this->execute( $select );
    foreach ( $statement as $row ) {
      if ( !array_key_exists( $row->name, $this->tables ) ) {
        $this->tables[ $row->name ] = new BasicTable( $this, $row->name );
      }
    }
    return $this->tables;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\AbstractDatabase::loadTable()
   */
  protected function loadTable( $name ) {

  }

}
