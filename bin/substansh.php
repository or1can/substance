#!/usr/bin/env php
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

/**
 * Substance Front Controller.
 */

// Report all errors.
error_reporting(E_ALL | E_STRICT);

require dirname( __DIR__ ) . '/vendor/autoload.php';

use Substance\Core\Alert\Alert;
use Substance\Core\Bootstrap;
use Substance\Core\Database\Connection;
use Substance\Core\Module;

// Bootstap the system.
Bootstrap::initialise();

var_dump( Module::findModules() );

$alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );

echo $alert;

$connection = Connection::getConnection();

var_dump( $connection );

var_dump( $connection->listDatabases() );

$database = $connection->getDatabase();

var_dump( $database->listTables() );

$database->createTable( 'sample' );

var_dump( $database->listTables() );

throw $alert;