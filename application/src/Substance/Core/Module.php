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
 * A Substance module, from which all modules inherit.
 */
abstract class Module {

  /**
   * Finds all available modules for the current site.
   *
   * @return array an array of module files.
   */
  public static function findModules() {
    $root = Environment::getEnvironment()->getApplicationRoot();
    return $root->find('*.module');
  }

  /**
   * Checks the run-time requirements for this module.
   */
  public static function requirements() {}

}
