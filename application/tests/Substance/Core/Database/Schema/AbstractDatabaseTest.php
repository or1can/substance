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

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\Schema\Types\Char;
use Substance\Core\Database\Schema\Types\Date;
use Substance\Core\Database\Schema\Types\DateTime;
use Substance\Core\Database\Schema\Types\Float;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\Schema\Types\Numeric;
use Substance\Core\Database\Schema\Types\Text;
use Substance\Core\Database\Schema\Types\Time;
use Substance\Core\Database\Schema\Types\VarChar;
use Substance\Core\Database\SQL\DataDefinitions\CreateTable;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\EqualsExpression;
use Substance\Core\Database\SQL\Expressions\FunctionExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\DataDefinitions\DropTable;

/**
 * Base for database tests.
 */
abstract class AbstractDatabaseTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Connection the database connection for testing
   */
  protected $connection;

  /**
   * @var Database the default database for testing.
   */
  protected $database;

  protected $test_database_names = array( 'test' );

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
          'CREATE TABLE "table" ("col" INTEGER NULL DEFAULT NULL, "col2" CHAR(5) NULL DEFAULT NULL, "col3" VARCHAR(10) NULL DEFAULT NULL, "col4" NUMERIC(10, 5) NULL DEFAULT NULL, "col5" TEXT NULL DEFAULT NULL, "col6" DATE NULL DEFAULT NULL, "col7" DATETIME NULL DEFAULT NULL, "col8" TIME NULL DEFAULT NULL)',
          'CREATE TABLE "table.dot" ()',
        )
      )
    );
  }

  /**
   * Returns the expected values for the build drop table test.
   *
   * @return multitype:multitype:multitype:string multitype:number
   * @see AbstractDatabaseTest::testBuildDropTable()
   */
  public function getBuildDropTableValues() {
    return array(
      array(
        array(
          'DROP TABLE "table"',
        )
      )
    );
  }

  /**
   * Returns the expected values for the build function expression test.
   *
   * @return multitype:multitype:multitype:string multitype:number
   * @see AbstractDatabaseTest::testBuildFunctionExpression()
   */
  public function getBuildFunctionExpressionValues() {
    return array(
      array(
        array(
          'FUNC()',
          'FUNC( \'arg1\' )',
          'FUNC( \'arg1\', 10 )',
          'FUNC( \'string\', `column1` )',
        )
      )
    );
  }

  /**
   * Returns the expected values for the build select test.
   *
   * @return multitype:multitype:multitype:string multitype:number
   * @see AbstractDatabaseTest::testBuildSelect()
   */
  public function getBuildSelectValues() {
    return array(
      array(
        array(
          'SELECT "column1", "t"."column2", "t2"."column3" FROM "table1" AS "t1" INNER JOIN "table2" AS "t" LEFT JOIN "table3" AS "t2" ON "column1" = "column2" WHERE "column1" = :dbph GROUP BY "column1" ORDER BY "column1" ASC LIMIT 5 OFFSET 1',
          array( ':dbph' => 5 ),
        )
      )
    );
  }

  abstract public function initialise();

  /* (non-PHPdoc)
   * @see PHPUnit_Framework_TestCase::setUp()
   */
  public function setUp() {
    $this->initialise();
    // Get the default database for the connection.
    $this->database = $this->connection->getDatabase();
  }

  /**
   * Test building a char.
   */
  public function testBuildChar() {
    $char = new Char( 10 );
    $this->assertEquals( 'CHAR(10)', $this->database->buildChar( $char ) );
  }

  /**
   * Test the building a create table.
   *
   * @dataProvider getBuildCreateTableValues
   */
  public function testBuildCreateTable( $expected_sql ) {
    $table = new BasicTable( $this->database, 'table' );
    $definition = new CreateTable( $table );
    $sql = $definition->build( $this->database );
    $this->assertEquals( $expected_sql[ 0 ], $sql );

    // Add some columns
    $table->addColumnByName( 'col', new Integer() );
    $table->addColumnByName( 'col2', new Char( 5 ) );
    $table->addColumnByName( 'col3', new VarChar( 10 ) );
    $table->addColumnByName( 'col4', new Numeric( 10, 5 ) );
    $table->addColumnByName( 'col5', new Text() );
    $table->addColumnByName( 'col6', new Date() );
    $table->addColumnByName( 'col7', new DateTime() );
    $table->addColumnByName( 'col8', new Time() );
    $sql = $definition->build( $this->database );
    $this->assertEquals( $expected_sql[ 1 ], $sql );

    $table = new BasicTable( $this->database, 'table.dot' );
    $definition = new CreateTable( $table );
    $sql = $definition->build( $this->database );
    $this->assertEquals( $expected_sql[ 2 ], $sql );
  }

  /**
   * Test building a date.
   */
  public function testBuildDate() {
    $date = new Date();
    $this->assertEquals( 'DATE', $this->database->buildDate( $date ) );
  }

  /**
   * Test building a datetime.
   */
  public function testBuildDateTime() {
    $datetime = new DateTime();
    $this->assertEquals( 'DATETIME', $this->database->buildDateTime( $datetime ) );
  }

  /**
   * Test the building a drop table.
   *
   * @dataProvider getBuildDropTableValues
   */
  public function testBuildDropTable( $expected_sql ) {
    $definition = new DropTable('table');
    $sql = $definition->build( $this->database );
    $this->assertEquals( $expected_sql[ 0 ], $sql );
  }

  /**
   * Test building a float.
   */
  public function testBuildFloat() {
    $float = new Float( Size::size( Size::TINY ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
    $float->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'FLOAT', $this->database->buildFloat( $float ) );
  }

  /**
   * Test building a function expression.
   *
   * @dataProvider getBuildFunctionExpressionValues
   */
  public function testBuildFunctionExpression( $expected_sql ) {
    $function = new FunctionExpression('FUNC');
    $this->assertEquals( $expected_sql[ 0 ], $this->database->buildFunctionExpression( $function ) );
    $function->addArgument( new LiteralExpression('arg1') );
    $this->assertEquals(  $expected_sql[ 1 ], $this->database->buildFunctionExpression( $function ) );
    $function->addArgument( new LiteralExpression( 10 ) );
    $this->assertEquals(  $expected_sql[ 2 ], $this->database->buildFunctionExpression( $function ) );
    $function = new FunctionExpression( 'FUNC', new LiteralExpression('string'), new ColumnNameExpression('column1') );
    $this->assertEquals(  $expected_sql[ 3 ], $this->database->buildFunctionExpression( $function ) );
  }

  /**
   * Test building a integer.
   */
  public function testBuildInteger() {
    $integer = new Integer( Size::size( Size::TINY ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::SMALL ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::MEDIUM ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::NORMAL ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
    $integer->setSize( Size::size( Size::BIG ) );
    $this->assertEquals( 'INTEGER', $this->database->buildInteger( $integer ) );
  }

  /**
   * Test building a numeric.
   */
  public function testBuildNumeric() {
    $numeric = new Numeric( 10, 5 );
    $this->assertEquals( 'NUMERIC(10, 5)', $this->database->buildNumeric( $numeric ) );
  }

  /**
   * Test building a select statement.
   *
   * @dataProvider getBuildSelectValues
   */
  public function testBuildSelect( $expected_sql ) {
    // Build a select query with everything we can think of.
    $select = Select::select( 'table1', 't1' );
    $sql = $select
      ->innerJoinByName( 'table2', $table2 = $select->uniqueTableAlias('t') )
      ->leftJoinByNameOn( 'table3', $table3 = $select->uniqueTableAlias('t'), new EqualsExpression( new ColumnNameExpression('column1'), new ColumnNameExpression('column2') ) )
      ->addColumn( new ColumnNameExpression('column1') )
      ->addColumnByName( 'column2', NULL, $table2 )
      ->addColumnByName( 'column3', NULL, $table3 )
      ->where( new EqualsExpression( new ColumnNameExpression('column1'), new LiteralExpression( 5 ) ) )
      ->groupBy( new ColumnNameExpression('column1') )
      ->orderBy( new ColumnNameExpression('column1') )
      ->limit( 5 )
      ->offset( 1 )
      ->build( $this->database );
    $this->assertEquals( $expected_sql[ 0 ], $sql );
    $this->assertEquals( $expected_sql[ 1 ], $select->getArguments() );
  }

  /**
   * Test building a text.
   */
  public function testBuildText() {
    $text = new Text();
    $this->assertEquals( 'TEXT', $this->database->buildText( $text ) );
  }

  /**
   * Test building a time.
   */
  public function testBuildTime() {
    $time = new Time();
    $this->assertEquals( 'TIME', $this->database->buildTime( $time ) );
  }

  /**
   * Test building a varchar.
   */
  public function testBuildVarChar() {
    $varchar = new VarChar( 10 );
    $this->assertEquals( 'VARCHAR(10)', $this->database->buildVarChar( $varchar ) );
  }

  /**
   * Test creating a table.
   */
  public function testCreateTable() {
    $this->assertCount( 0, $this->database->listTables() );
    $table = $this->database->createTable('table');
    $table->addColumnByName( 'col2', new Integer() );
    $this->database->applyDataDefinitions();
    $this->assertCount( 1, $this->database->listTables() );
    $this->assertTrue( $this->database->hasTableByName('table') );

    // Now try another table with multiple columns.
    $table = $this->database->createTable('table2');
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $table->addColumn( $column );
    $table->addColumnByName( 'col2', new Integer() );
    $table->addColumnByName( 'col3', new Integer( Size::size( Size::TINY ) ) );
    $table->addColumnByName( 'col4', new Integer( Size::size( Size::BIG ) ) );
    $this->database->applyDataDefinitions();
    $this->assertCount( 2, $this->database->listTables() );
    $this->assertTrue( $this->database->hasTableByName('table2') );
  }

  /**
   * Test creating a table with no columns.
   *
   * @expectedException Substance\Core\Alert\Alert
   */
  public function testCreateTableNoColumns() {
    $table = $this->database->createTable('table');
    $this->database->applyDataDefinitions();
  }

  /**
   * Test listing tables in a database.
   */
  public function testListTables() {
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 0, $tables );

    // Create a table and check it is listed now.
    $table = $this->database->createTable('table');
    $column = new ColumnImpl( $table, 'col', new Integer() );
    $table->addColumn( $column );
    $this->database->applyDataDefinitions();
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 1, $tables );
    $this->assertArrayHasKey( 'table', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table'] );

    // Create another table and check it is listed now.
    $table2 = $this->database->createTable('table2');
    $column = new ColumnImpl( $table2, 'col', new Integer() );
    $table2->addColumn( $column );
    $this->database->applyDataDefinitions();
    $tables = $this->database->listTables();
    $this->assertTrue( is_array( $tables ) );
    $this->assertCount( 2, $tables );
    $this->assertArrayHasKey( 'table', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table'] );
    $this->assertArrayHasKey( 'table2', $tables );
    $this->assertInstanceOf( 'Substance\Core\Database\Schema\BasicTable', $tables['table2'] );
  }

}
