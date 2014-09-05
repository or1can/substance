<?php
namespace Substance\Modules\Configuration;

use Pimple;
use Substance\Core\Alert\Alert;
use Substance\Core\Database\Database;

class Config extends Pimple {

  public function __construct( array $values = array() ) {
    parent::__construct( $values );
    $this['database'] = new \Pimple();
    $this->load();
  }

  private function load() {
    require 'Sites/_default/settings.php';

    if ( !array_key_exists( '*', $databases ) ) {
      throw Alert::alert('You must define database name "*" in your database configuration' );
    }
    foreach ( $databases as $connection_name => $server_types ) {
      $this['database'][ $connection_name ] = new \Pimple();
      foreach ( $server_types as $server_type => $connection ) {
        $this['database'][ $connection_name ][ $server_type ] = $this->share(function ($c) use ( $connection ) {
          return Database::getConnection( $connection );
        });
      }
    }
  }
}
