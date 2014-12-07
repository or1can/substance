<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 Kevin Rogers
 *
 * Substance is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Substance is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Substance.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Substance\Core\Alert;

use Substance\Core\Alert\Alert;
use Substance\Core\Environment\CliEnvironment;
use Substance\Core\Environment\HttpEnvironment;

class AlertTest extends \PHPUnit_Framework_TestCase {

  /**
   * Crude test of basic Alert functionality in a CLI environment.
   */
  public function testCliAlert() {
    CliEnvironment::initialise();
    $alert = Alert::alert('ahhhh')->culprit( 'who', 'me' );
    $alert = $alert->__toString();

    $this->assertContains( 'Message : ahhhh', $alert );
    $this->assertRegExp( '#Origin : .*?tests/Substance/Core/Alert/AlertTest.php\(\d+\)#', $alert );
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
    $this->assertRegExp( '#<tr><td>Origin</td><td>.*?tests/Substance/Core/Alert/AlertTest.php\(\d+\)</td></tr>#', $alert );
    $this->assertContains( '<tr><td>WHO</td><td>me</td></tr>', $alert );
  }

}
