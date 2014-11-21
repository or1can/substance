<?php
use Substance\Core\Alert\Alert;
use Substance\Core\Environment\CliEnvironment;
use Substance\Core\Environment\HttpEnvironment;

class AlertTest extends PHPUnit_Framework_TestCase {

  /**
   * Crude test of basic Alert functionality in a CLI environment.
   */
  public function testCliAlert() {
    CliEnvironment::initialise();
    $alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );
    $alert = $alert->__toString();

    $this->assertContains( 'Message : ahhhh', $alert );
    $this->assertRegExp( '#Origin : .*?tests/Core/Alert/AlertTest.php\(\d+\)#', $alert );
    $this->assertContains( 'WHO : me', $alert );
  }

  /**
   * Crude test of basic Alert functionality in an HTTP environment.
   */
  public function testHttpAlert() {
    HttpEnvironment::initialise();
    $alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );
    $alert = $alert->__toString();

    $this->assertContains( '<tr><td>Message</td><td>ahhhh</td></tr>', $alert );
    $this->assertRegExp( '#<tr><td>Origin</td><td>.*?tests/Core/Alert/AlertTest.php\(\d+\)</td></tr>#', $alert );
    $this->assertContains( '<tr><td>WHO</td><td>me</td></tr>', $alert );
  }

}
