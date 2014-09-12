#!/usr/bin/env php
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
use Substance\Core\Alert\AlertHandler;
use Substance\Core\Environment\Environment;
use Substance\Core\Module;
use Substance\Core\Presentation\Element;
use Substance\Core\Presentation\ElementBuilder;
use Substance\Core\Presentation\Elements\Page;
use Substance\Core\Presentation\Elements\TableCell;
use Substance\Modules\Configuration\Config;
use Substance\Themes\HTML\HTMLTheme;
use Substance\Themes\Text\TextTheme;

$alert_handler = new AlertHandler();
$alert_handler->register();

$config = new Config();

var_dump( $config );

Environment::initialiseTextEnvironment();
$environment = Environment::getEnvironment();

var_dump( Module::findModules() );

$alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );

var_export( $alert->present() );

echo $alert;

$environment->setOutputTheme( TextTheme::create() );

echo $alert;

$connection = $config['database']['*']['master'];

var_dump( $connection->query('SELECT * FROM information_schema.TABLES LIMIT 1') );

$tablecell = TableCell::build('Origin');

var_export( $tablecell );

echo $environment->getOutputTheme()->render( $tablecell );

$tablecell = TableCell::build(array(
  '#type' => 'Substance\Core\Presentation\Elements\TableCell',
  '#elements' => 'stuff',
));

var_export( $tablecell );

$environment->outputElement( $tablecell );

$tablecell = ElementBuilder::build(array(
  '#type' => 'Substance\Core\Presentation\Elements\TableCell',
  '#elements' => 'stuff',
));

var_export( $tablecell );

$environment->outputElement( $tablecell );


$environment->setOutputTheme( HTMLTheme::create() );


$tablerow = ElementBuilder::build(array(
  '#type' => 'Substance\Core\Presentation\Elements\TableRow',
  '#row' => array( 'stuff', 'morestuff' ),
));

var_export( $tablerow );

$environment->outputElement( $tablerow );

$table = ElementBuilder::build(array(
  '#type' => 'Substance\Core\Presentation\Elements\Table',
  '#rows' => array(
    array( 'stuff', 'morestuff' ),
    array( 'stuff2', 'morestuff2' )
  ),
));

var_export( $table );

$environment->outputElement( $table );

$page = new Page();

$environment->outputElement( $page );
