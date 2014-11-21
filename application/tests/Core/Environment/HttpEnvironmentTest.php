<?php
use Substance\Core\Alert\Alert;
use Substance\Core\Environment\Environment;
use Substance\Core\Environment\HttpEnvironment;

class HttpEnvironmentTest extends PHPUnit_Framework_TestCase {

  /**
   * Check the expected theme in an HTTP environment.
   */
  public function testTheme() {
    HttpEnvironment::initialise();

    $this->assertInstanceOf( 'Substance\Themes\HTML\HTMLTheme', Environment::getEnvironment()->getOutputTheme() );
  }

}
