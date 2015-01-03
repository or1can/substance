<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
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

namespace Substance\Core\Database\Queries;

use Substance\Core\Database\Expressions\AllColumnsExpression;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Database\TestConnection;
use Substance\Core\Database\Expressions\EqualsExpression;
use Substance\Core\Database\Expressions\ColumnExpression;
use Substance\Core\Database\Expressions\LiteralExpression;

/**
 * Tests select queries.
 */
class SelectTest extends \PHPUnit_Framework_TestCase {

  protected $connection;

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->connection = new TestConnection();
  }

  /**
   * Test a build with a database prefix on the table.
   */
  public function testBuildWithDatabaseAndTable() {
    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES`', $sql );
  }

  /**
   * Test a build with no limit and no offset.
   */
  public function testBuildNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build with no limit and an offset.
   */
  public function testBuildNoLimitWithOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table`', $sql );
  }

  /**
   * Test a build with a limit and no offset.
   */
  public function testBuildWithLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1', $sql );
  }

  /**
   * Test a build with a limit and offset.
   */
  public function testBuildWithLimitWithOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );
    $select->offset( 2 );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` LIMIT 1 OFFSET 2', $sql );
  }

  /**
   * Test a build with where clause, no limit and offset.
   */
  public function testBuildWithWhereNoLimitNoOffset() {
    $select = new Select('table');
    $select->addExpression( new AllColumnsExpression() );
    $select->where( new EqualsExpression( new ColumnExpression('column1'), new LiteralExpression( 5 ) ) );

    $sql = $select->build( $this->connection );

    $this->assertEquals( 'SELECT * FROM `table` WHERE `column1` = 5', $sql );
  }

}
