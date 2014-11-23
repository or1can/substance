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

/**
 * Substance Front Controller.
 */

// Report all errors.
error_reporting(E_ALL | E_STRICT);

require '../vendor/autoload.php';

use Substance\Core\Alert\Alert;
use Substance\Core\Bootstrap;
use Substance\Core\Database\Database;
use Substance\Core\Database\Expressions\AllColumnsExpression;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Environment\Environment;
use Substance\Core\Module;

// Bootstap the system.
Bootstrap::initialise();

var_dump( Module::findModules() );

$alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );

var_export( $alert->present() );

echo $alert;

$connection = Database::getConnection( '*', 'master' );

var_dump( $connection->query('SELECT * FROM information_schema.TABLES LIMIT 1') );

$select = new Select('information_schema.TABLES');
$select->addExpression( new AllColumnsExpression() );
$select->limit( 1 );
$select->offset( 2 );
echo $select, "\n\n";
echo $select->build( $connection ), "\n\n";

var_dump( $connection->getSchema()->listTables() );


throw $alert;