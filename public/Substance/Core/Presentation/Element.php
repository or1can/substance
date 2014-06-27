<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2005 - 2014 Kevin Rogers
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

namespace Substance\Core\Presentation;

use Substance\Core\Alert\Alert;

/**
 * The Element is the base of the rendering system. All output is handled by
 * building a structured Element, which is then rendered appropriately for the
 * current context.
 */
abstract class Element {

  /**
   * Returns a new instance of the element. This method should be overridden in
   * all subclasses.
   * @return Element A new instance of the element.
   */
  public static function create() {
    throw new Alert('Element cannot be constructed.');
  }

  public function render( Theme $theme ) {

  }

}
