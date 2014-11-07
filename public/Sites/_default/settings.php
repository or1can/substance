<?php

namespace Sites\_default;

use Substance\Core\Environment\Environment;
use Substance\Core\Settings;

class DefaultSettings extends Settings {

  public function getDatabaseSettings() {
    return array(
      '*' => array(
        'master' => array(
          'driverclass' => '\Substance\Core\Database\MySQL\Connection',
          'database' => 'mydb',
          'username' => 'myuser',
          'password' => 'mypass',
          'host' => '127.0.0.1',
          'port' => '3306',
          'prefix' => NULL,
        ),
        'slave' => array(
          'driverclass' => 'Substance\Core\Database\MySQL\Connection',
          'database' => 'mydb',
          'username' => 'myuser',
          'password' => 'mypass',
          'host' => '127.0.0.1',
          'port' => '3306',
          'prefix' => NULL,
        ),
      ),
      'named' => array(
        'master' => array(
          'driverclass' => 'Substance\Core\Database\MySQL\Connection',
          'database' => 'mydb',
          'username' => 'myuser',
          'password' => 'mypass',
          'host' => '127.0.0.1',
          'port' => '3306',
          'prefix' => NULL,
        ),
        'slave' => array(
          'driverclass' => 'Substance\Core\Database\MySQL\Connection',
          'database' => 'mydb',
          'username' => 'myuser',
          'password' => 'mypass',
          'host' => '127.0.0.1',
          'port' => '3306',
          'prefix' => NULL,
        ),
      ),
    );
  }
}

Environment::getEnvironment()->setSettings( new DefaultSettings() );