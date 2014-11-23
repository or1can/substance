<?php
use Substance\Core\Alert\Alert;
use Substance\Core\Database\Drivers\MySQL\Connection;
use Substance\Core\Database\Expressions\AllColumnsExpression;
use Substance\Core\Database\Queries\Select;
use Substance\Core\Environment\CliEnvironment;
use Substance\Core\Environment\Environment;

class SelectTestConnection extends Connection {

  public function __construct() {

  }

}

class SelectTest extends PHPUnit_Framework_TestCase {

  /**
   * Check the expected theme in a CLI environment.
   */
  public function testBuild() {
    $connection = new SelectTestConnection();

    $select = new Select('information_schema.TABLES');
    $select->addExpression( new AllColumnsExpression() );
    $select->limit( 1 );
    $select->offset( 2 );

    $sql = $select->build( $connection );

    $this->assertEquals( 'SELECT * FROM `information_schema`.`TABLES` LIMIT 1 OFFSET 2', $sql );
  }

}
