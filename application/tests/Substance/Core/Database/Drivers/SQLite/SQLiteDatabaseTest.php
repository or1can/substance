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

use Substance\Core\Database\Schema\AbstractDatabaseTest;
use Substance\Core\Database\Schema\Size;
use Substance\Core\Database\Schema\Types\Char;
use Substance\Core\Database\Schema\Types\Date;
use Substance\Core\Database\Schema\Types\DateTime;
use Substance\Core\Database\Schema\Types\Float;
use Substance\Core\Database\Schema\Types\Time;
use Substance\Core\Database\Schema\Types\VarChar;

/**
 * Base for SQLite database tests.
 */
class SQLiteDatabaseTest extends AbstractDatabaseTest {

  /**
   * Returns the expected values for the build create table test.
   *
   * @return multitype:multitype:multitype:string multitype:number
   * @see AbstractDatabaseTest::testBuildCreateTable()
   */
  public function getBuildCreateTableValues() {
    return array(
      array(
        array(
          'CREATE TABLE "table" ()',
          'CREATE TABLE "table" ("col" INTEGER NULL DEFAULT NULL, "col2" TEXT NULL DEFAULT NULL, "col3" TEXT NULL DEFAULT NULL, "col4" NUMERIC(10, 5) NULL DEFAULT NULL, "col5" TEXT NULL DEFAULT NULL, "col6" TEXT NULL DEFAULT NULL, "col7" TEXT NULL DEFAULT NULL, "col8" TEXT NULL DEFAULT NULL)',
          'CREATE TABLE "table.dot" ()',
        )
      )
    );
  }

  /* (non-PHPdoc)
   * @see AbstractConnectionTest::initialise()
   */
  public function initialise() {
    // Remove known test databases.
    if ( !file_exists('/tmp/substance') ) {
      mkdir('/tmp/substance');
    }
    $clear_databases = $this->test_database_names;
    $clear_databases[] = 'mydb';
    foreach ( $clear_databases as $database ) {
      $db = "/tmp/substance/$database.db";
      if ( file_exists( $db ) ) {
        $this->assertTrue( unlink("/tmp/substance/$database.db"), 'Failed to remove old SQLite database.' );
      }
    }
    // Open the test database.
    $this->connection = new SQLiteConnection('/tmp/substance/mydb.db');
  }

  /**
   * Test building a char.
   */
  public function testBuildChar() {
    $char = new Char( 10 );
    $this->assertEquals( 'TEXT', $this->database->buildChar( $char ) );
  }

  /**
   * Test building a date.
   */
  public function testBuildDate() {
    $date = new Date();
    $this->assertEquals( 'TEXT', $this->database->buildDate( $date ) );
  }

  /**
   * Test building a datetime.
   */
  public function testBuildDateTime() {
    $datetime = new DateTime();
    $this->assertEquals( 'TEXT', $this->database->buildDateTime( $datetime ) );
  }

  /**
   * Test building a float for MySQL.
   */
  public function testBuildFloat() {
    $float = new Float( Size::size( Size::TINY ) );
    $this->assertEquals( 'REAL', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'REAL', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'REAL', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'REAL', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'REAL', $this->database->buildFloat( $float ) );
  }

  /**
   * Test building a time.
   */
  public function testBuildTime() {
    $time = new Time();
    $this->assertEquals( 'TEXT', $this->database->buildTime( $time ) );
  }

  /**
   * Test building a varchar.
   */
  public function testBuildVarChar() {
    $varchar = new VarChar( 10 );
    $this->assertEquals( 'TEXT', $this->database->buildVarChar( $varchar ) );
  }

  /**
   * Test the quote characater.
   */
  public function testQuoteChar() {
    $this->assertEquals( '"', $this->database->quoteChar() );
  }

  /**
   * Test quoting table names.
   */
  public function testQuoteTable() {
    // Test a plain table.
    $quoted = $this->database->quoteTable('table');
    $this->assertEquals( '"table"', $quoted );

    // Test a table with database.
    $quoted = $this->database->quoteTable('db.table');
    $this->assertEquals( '"db"."table"', $quoted );
  }

}
