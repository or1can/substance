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
use Substance\Core\Environment\Environment;
use Substance\Core\Module;
use Substance\Modules\Configuration\Config;
use Substance\Themes\HTML\HTMLTheme;
use Substance\Themes\Text\TextTheme;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

// $run = new Run;
// $handler = new PrettyPageHandler;

// $run->pushHandler( $handler );
// $run->register();

$config = new Config();

var_dump( $config );

// throw new Exception("foo");

try {
  Environment::initialiseTextEnvironment();
  $environment = Environment::getEnvironment();

  var_dump( Module::findModules() );

  $alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );

  var_export( $alert->present() );

  // throw $alert;

  echo $alert;


  $environment->setOutputTheme( TextTheme::create() );

  echo $alert;

  $connection = $config['database']['*']['master'];

  var_dump( $connection->query('SELECT * FROM information_schema.TABLES LIMIT 1') );

} catch ( Alert $a ) {
  echo $a;
}
