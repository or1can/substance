<?php
namespace Substance\Modules\Configuration;

use Pimple;

class Config {

  private $container = NULL;

  public function __construct() {
    $this->container = new Pimple();
  }

}
