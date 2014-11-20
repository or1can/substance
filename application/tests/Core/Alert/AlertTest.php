<?php
use Substance\Core\Alert\Alert;
use Substance\Core\Environment\CliEnvironment;

class AlertTest extends PHPUnit_Framework_TestCase {

  /**
   * Crude test of basic Alert functionality.
   */
  public function testAlert() {
    CliEnvironment::initialise();
    $alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );
    $alert = $alert->__toString();

    $this->assertContains( 'Message : ahhh', $alert );
    $this->assertRegExp( '#Origin : .*?tests/Core/Alert/AlertTest.php#', $alert );
    $this->assertContains( 'WHO : me', $alert );
  }

}
