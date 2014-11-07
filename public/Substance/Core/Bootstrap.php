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

namespace Substance\Core;

use Substance\Core\Environment\Environment;

/**
 * This class is responsible for bootstapping Substance into a useable state.
 */
class Bootstrap {

  /**
   * Bootstrap process entry point - initialises the system.
   */
  public static function initialise() {
    // FIXME - This should load the appropriate settings based on the supplied
    // domain, but for now we just load the defaults for testing.
    require 'Sites/_default/settings.php';
    // Set the application temporary files folder
    if ( is_dir( '/tmp/substance' ) ) {
      $environment = Environment::getEnvironment();
      $environment->setApplicationTempFolder(
        // FIXME - This needs to be configured and already exist!
        new Folder( '/tmp/substance' )
      );
    } else {
      print "WARNING: Temporary folder not available\n\n";
    }
  }

}
