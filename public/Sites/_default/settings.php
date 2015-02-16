<?php

namespace Sites\_default;

use Substance\Core\Database\Drivers\MySQL\MySQLConnection;
use Substance\Core\Environment\Environment;
use Substance\Core\Settings;

class DefaultSettings extends Settings {

  /* (non-PHPdoc)
   * @see \Substance\Core\Settings::getDatabaseMaster()
   */
  public function getDatabaseMaster( $name = 'default' ) {
    switch ( $name ) {
      case 'named':
        return new MySQLConnection( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        break;
      case 'default':
        return new MySQLConnection( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        break;
      default:
        // TODO - This default case is important, as putting the default
        // connection here would result in it being used for even undefined
        // connection names.
        return NULL;
        break;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Settings::getDatabaseSlave()
   */
  public function getDatabaseSlave( $name = 'default' ) {
    switch ( $name ) {
      case 'named':
        return new MySQLConnection( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        break;
      case 'default':
        return new MySQLConnection( '127.0.0.1', 'mydb', 'myuser', 'mypass' );
        break;
      default:
        // TODO - This default case is important, as putting the default
        // connection here would result in it being used for even undefined
        // connection names.
        return NULL;
        break;
    }
  }

}

Environment::getEnvironment()->setSettings( new DefaultSettings() );