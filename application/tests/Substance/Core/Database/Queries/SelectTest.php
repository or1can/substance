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

/**
 * Tests select queries.
 */
class SelectTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test a basic build of a SQL query.
   */
  public function testBuild() {
    $connection = new TestConnection();

    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );
    $select->offset( 2 );

    $sql = $select->build( $connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES` LIMIT 1 OFFSET 2', $sql );
  }

}