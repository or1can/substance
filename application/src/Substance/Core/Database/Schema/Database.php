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

namespace Substance\Core\Database\Schema;

use Substance\Core\Alert\Alert;
use Substance\Core\Database\Connection;
use Substance\Core\Database\Schema\Table;
use Substance\Core\Database\Schema\Types\Char;
use Substance\Core\Database\Schema\Types\Date;
use Substance\Core\Database\Schema\Types\DateTime;
use Substance\Core\Database\Schema\Types\Float;
use Substance\Core\Database\Schema\Types\Integer;
use Substance\Core\Database\Schema\Types\Numeric;
use Substance\Core\Database\Schema\Types\Text;
use Substance\Core\Database\Schema\Types\Time;
use Substance\Core\Database\Schema\Types\VarChar;
use Substance\Core\Database\SQL\Buildable;
use Substance\Core\Database\SQL\Columns\AllColumns;
use Substance\Core\Database\SQL\Columns\AllColumnsFromTable;
use Substance\Core\Database\SQL\Columns\ColumnWithAlias;
use Substance\Core\Database\SQL\Components\ComponentList;
use Substance\Core\Database\SQL\Components\OrderBy;
use Substance\Core\Database\SQL\DataDefinition;
use Substance\Core\Database\SQL\DataDefinitions\CreateTable;
use Substance\Core\Database\SQL\DataDefinitions\DropTable;
use Substance\Core\Database\SQL\Expressions\ColumnNameExpression;
use Substance\Core\Database\SQL\Expressions\LiteralExpression;
use Substance\Core\Database\SQL\Expressions\NameExpression;
use Substance\Core\Database\SQL\InfixExpression;
use Substance\Core\Database\SQL\PostfixExpression;
use Substance\Core\Database\SQL\PrefixExpression;
use Substance\Core\Database\SQL\Queries\Select;
use Substance\Core\Database\SQL\Query;
use Substance\Core\Database\SQL\TableReferences\InnerJoin;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\On;
use Substance\Core\Database\SQL\TableReferences\JoinConditions\Using;
use Substance\Core\Database\SQL\TableReferences\LeftJoin;
use Substance\Core\Database\SQL\TableReferences\TableName;

/**
 * Represents a database schema.
 */
interface Database {

  /**
   * Applies any outstanding data definitions.
   */
  public function applyDataDefinitions();

  /**
   * Builds the specified buildable.
   *
   * This method allows the Database control over how each Buildable is built.
   * The core database implementations ask each Buildable how to build itself,
   * and each Buildable calls back to a Database method for that specific type
   * of Buildable.
   *
   * A Database could take control over this process by examining the Buildable
   * and choosing different Database methods, e.g.
   *
   * switch ( get_class( $buildable ) ) {
   *   case 'Substance\Core\Database\SQL\Expressions\NameExpression':
   *     // Build a NameExpression as a LiteralExpression.
   *     return $this->renderLiteral( $buildable );
   *     break;
   *   default:
   *     // Build other Buildables using their default behaviour.
   *     return $buildable->render( $this );
   *     break;
   * }
   *
   * @param Buildable $buildable the Buildable to build.
   * @return string the built buildable.
   */
  public function build( Buildable $buildable );

  /**
   * Build the specified AllColumns object.
   *
   * @param AllColumns $all_columns the AllColumns to build.
   * @return string the built AllColumns.
   */
  public function buildAllColumns( AllColumns $all_columns );

  /**
   * Build the specified AllColumnsFromTable object.
   *
   * @param AllColumnsFromTable $all_columns_from_table the AllColumnsFromTable
   * to build.
   * @return string the built AllColumnsFromTable.
   */
  public function buildAllColumnsFromTable( AllColumnsFromTable $all_columns_from_table );

  /**
   * Build the specified Char object.
   *
   * @param Char $char the Char to build.
   * @return string the built Char.
   */
  public function buildChar( Char $char );

  /**
   * Build the specified Column object.
   *
   * @param Column $column_name_expression the Column to build.
   * @return string the built Column.
   */
  public function buildColumn( Column $column );

  /**
   * Build the specified ColumnNameExpression object.
   *
   * @param ColumnNameExpression $column_name_expression the
   * ColumnNameExpression to build.
   * @return string the built ColumnNameExpression.
   */
  public function buildColumnNameExpression( ColumnNameExpression $column_name_expression );

  /**
   * Build the specified ColumnWithAlias object.
   *
   * @param ColumnWithAlias $column_with_alias the ColumnWithAlias to build.
   * @return string the built ColumnWithAlias.
   */
  public function buildColumnWithAlias( ColumnWithAlias $column_with_alias );

  /**
   * Build the specified ComponentList object.
   *
   * @param ComponentList $comonent_list the ComponentList to build.
   * @return string the built ComponentList.
   */
  public function buildComponentList( ComponentList $comonent_list );

  /**
   * Build the specified CreateTable object.
   *
   * @param CreateTable $create_table the CreateTable to build.
   * @return string the built CreateTable.
   */
  public function buildCreateTable( CreateTable $create_table );

  /**
   * Build the specified Date object.
   *
   * @param Date $date the Date to build.
   * @return string the built Date.
   */
  public function buildDate( Date $date );

  /**
   * Build the specified DateTime object.
   *
   * @param DateTime $datetime the DateTime to build.
   * @return string the built DateTime.
   */
  public function buildDateTime( DateTime $datetime );

  /**
   * Build the specified DropTable object.
   *
   * @param DropTable $drop_table the DropTable to build.
   * @return string the built DropTable.
   */
  public function buildDropTable( DropTable $drop_table );

  /**
   * Build the specified Float object.
   *
   * @param Float $float the Float to build.
   * @return string the built Float.
   */
  public function buildFloat( Float $float );

  /**
   * Build the specified InfixExpression object.
   *
   * @param InfixExpression $infix_expression the InfixExpression to build.
   * @return string the built InfixExpression.
   */
  public function buildInfixExpression( InfixExpression $infix_expression );

  /**
   * Build the specified InnerJoin object.
   *
   * @param InnerJoin $inner_join the InnerJoin to build.
   * @return string the built InnerJoin.
   */
  public function buildInnerJoin( InnerJoin $inner_join );

  /**
   * Build the specified Integer object.
   *
   * @param Integer $integer the Integer to build.
   * @return string the built Integer.
   */
  public function buildInteger( Integer $integer );

  /**
   * Build the specified LeftJoin object.
   *
   * @param LeftJoin $left_join the LeftJoin to build.
   * @return string the built LeftJoin.
   */
  public function buildLeftJoin( LeftJoin $left_join );

  /**
   * Build the specified LiteralExpression object.
   *
   * @param LiteralExpression $literal_expression the LiteralExpression to
   * build.
   * @return string the built LiteralExpression.
   */
  public function buildLiteralExpression( LiteralExpression $literal_expression );

  /**
   * Build the specified NameExpression object.
   *
   * @param NameExpression $name_expression the NameExpression to build.
   * @return string the built NameExpression.
   */
  public function buildNameExpression( NameExpression $name_expression );

  /**
   * Build the specified Numeric object.
   *
   * @param Numeric $numeric the Numeric to build.
   * @return string the built Numeric.
   */
  public function buildNumeric( Numeric $numeric );

  /**
   * Build the specified On object.
   *
   * @param On $on the On to build.
   * @return string the built On.
   */
  public function buildOn( On $on );

  /**
   * Build the specified OrderBy object.
   *
   * @param OrderBy $order_by the OrderBy to build.
   * @return string the built OrderBy.
   */
  public function buildOrderBy( OrderBy $order_by );

  /**
   * Build the specified PostfixExpression object.
   *
   * @param PostfixExpression $postfix_expression the PostfixExpression to
   * build.
   * @return string the built PostfixExpression.
   */
  public function buildPostfixExpression( PostfixExpression $postfix_expression );

  /**
   * Build the specified PrefixExpression object.
   *
   * @param PrefixExpression $prefix_expression the PrefixExpression to build.
   * @return string the built PrefixExpression.
   */
  public function buildPrefixExpression( PrefixExpression $prefix_expression );

  /**
   * Build the specified Select object.
   *
   * @param Select $select the Select to build.
   * @return string the built Select.
   */
  public function buildSelect( Select $select );

  /**
   * Build the specified TableName object.
   *
   * @param TableName $table_name the TableName to build.
   * @return string the built TableName.
   */
  public function buildTableName( TableName $table_name );

  /**
   * Build the specified Text object.
   *
   * @param Text $text the Text to build.
   * @return string the built Text.
   */
  public function buildText( Text $text );

  /**
   * Build the specified Time object.
   *
   * @param Time $time the Time to build.
   * @return string the built Time.
   */
  public function buildTime( Time $time );

  /**
   * Build the specified Using object.
   *
   * @param Using $using the Using to build.
   * @return string the built Using.
   */
  public function buildUsing( Using $using );

  /**
   * Build the specified VarChar object.
   *
   * @param VarChar $varchar the VarChar to build.
   * @return string the built VarChar.
   */
  public function buildVarChar( VarChar $varchar );

  /**
   * Creates a table with the specified name in the database specified in this
   * Schema's connection.
   *
   * @param string $name the new table name.
   * @return Table the new table.
   * @see Database::applyDataDefinitions
   */
  public function createTable( $name );

  /**
   * Drops the specified table.
   *
   * @param Table $table the table to drop.
   * @return self
   * @see Database::applyDataDefinitions
   */
  public function dropTable( Table $table );

  /**
   * Execute the specified query on this database.
   *
   * @param Query $query the query to execute.
   * @return Statement the result statement.
   * @see Database::getConnection()
   */
  public function execute( Query $query );

  /**
   * Returns the connection to this database.
   *
   * @return Connection the underlying connection.
   */
  public function getConnection();

  /**
   * Returns the name of this database. If the underlying database system does
   * not support names, this will return NULL.
   *
   * @return string the database name or NULL if not applicable
   */
  public function getName();

  /**
   * Returns the specified table.
   *
   * This method will cause the table schema to be loaded and cached.
   *
   * @param string $name the name of the table.
   * @return Table the table with the specified name.
   * @throws Alert if there is no table with the specified name.
   */
  public function getTable( $name );

  /**
   * Checks if a table with the specified name exists.
   *
   * This method will cause the table schema to be loaded and cached.
   *
   * @param string $name the name of the table.
   * @return boolean TRUE if a table with the specified name exists and FALSE
   * otherwise.
   */
  public function hasTableByName( $name );

  /**
   * Lists the tables in the database.
   *
   * This method will cause the table schema to be loaded and cached for all
   * tables in this database.
   *
   * @return Table[] associative array of table name to Table objects.
   */
  public function listTables();

  /**
   * Queue the specified data definition for later application.
   *
   * This allows for mulitiple data definitions to be combined into a single
   * operation.
   *
   * @param DataDefinition $data_definition the data definition to queue.
   */
  public function queueDataDefinition( DataDefinition $data_definition );

  /**
   * @see Connection::quoteChar()
   */
  public function quoteChar();

  /**
   * @see Connection::quoteName()
   */
  public function quoteName( $name );

  /**
   * @see Connection::quoteString()
   */
  public function quoteString( $value );

  /**
   * @see Connection::quoteTable()
   */
  public function quoteTable( $table );

}
