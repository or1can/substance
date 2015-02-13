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

use Substance\Core\Database\Database;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;

/**
 * Concrete instance of the Schema class for working with SQLite databases.
 */
class SQLiteDatabase extends Database {

  /**
   * Construct a new schema object to work with the specified connected
   * database.
   *
   * @param Connection $connection the database to work with
   */
  public function __construct( Connection $connection ) {
    parent::__construct( $connection );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema::createDatabases()
   */
  public function createDatabases( $name ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema::createTable()
   */
  public function createTable( $name ) {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema::listDatabases()
   */
  public function listDatabases() {
    // TODO
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema::listTables()
   */
  public function listTables() {
    // SQLite stores schema information in a hidden sqlite_master table in each
    // database. The first database in a connection has the name "main".
    $select = new Select( $this->getName() . '.sqlite_master' );
    $select->addColumn( new ColumnNameExpression('name') );
    $select->where( new EqualsExpression( new ColumnNameExpression('type'), new ColumnNameExpression('table') ) );
    $sql = $select->build( $this );
    echo $sql, "\n\n";
    $tables = array();
    foreach ( $this->query( $sql ) as $row ) {
      $tables[ $row->name ] = new MySQLTable( $this, $row->name );
    }
    return $tables;
  }

}
