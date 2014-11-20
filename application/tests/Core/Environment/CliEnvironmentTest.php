<?php
use Substance\Core\Alert\Alert;
use Substance\Core\Environment\CliEnvironment;
use Substance\Core\Environment\Environment;

class CliEnvironmentTest extends PHPUnit_Framework_TestCase {

  /**
   * Check the expected theme in a CLI environment.
   */
  public function testTheme() {
    CliEnvironment::initialise();

    $this->assertInstanceOf( 'Substance\Themes\Text\TextTheme', Environment::getEnvironment()->getOutputTheme() );
  }

}
