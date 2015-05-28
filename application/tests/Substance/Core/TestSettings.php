<?php

namespace Substance\Core;

use Substance\Core\Database\Drivers\MySQL\MySQLConnection;

class TestSettings extends Settings {

  /* (non-PHPdoc)
   * @see \Substance\Core\Settings::getDatabaseMaster()
   */
  public function getDatabaseMaster( $name = 'default' ) {
    switch ( $name ) {
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
    return $this->getDatabaseMaster( $name );
  }

}
