<?php

namespace Sites\_default;

use Substance\Core\Environment\Environment;
use Substance\Core\Settings;
use Substance\Core\Database\Drivers\MySQL\MySQLDatabase;

class DefaultSettings extends Settings {

  public function getDatabaseSettings( $name = NULL, $type = 'master' ) {
    switch ( $name ) {
      case 'named':
        if ( $type == 'master' ) {
          return new MySQLDatabase( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        } else {
          return new MySQLDatabase( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        }
        break;
      default:
        if ( $type == 'master' ) {
          return new MySQLDatabase( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        } else {
          return new MySQLDatabase( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        }
        break;
    }
  }

}

Environment::getEnvironment()->setSettings( new DefaultSettings() );