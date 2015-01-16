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

use Substance\Core\Database\Expressions\AllColumnsExpression;
use Substance\Core\Database\Schema;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Database\Expressions\EqualsExpression;
use Substance\Core\Database\Expressions\ColumnExpression;
use Substance\Core\Database\Expressions\LiteralExpression;

/**
 * The Schema class is used for working with a database schema.
 */
class MySQLSchema extends Schema {

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
    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('TABLE_SCHEMA'), new LiteralExpression( $this->connection->getDatabaseName() ) ) );
    $sql = $select->build( $this->connection );
    echo $sql, "\n\n";
    $tables = array();
    foreach ( $this->connection->query( $sql ) as $row ) {
      $tables[ $row->TABLE_NAME ] = new MySQLTable( $this->connection, $row );
    }
    return $tables;
  }

}
