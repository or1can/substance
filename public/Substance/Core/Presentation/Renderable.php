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

namespace Substance\Core\Presentation;

/**
 * A renderable object is an object that knows how to "render" itself using a
 * given theme.
 */
interface Renderable {

  /**
   * Renders this object using the specified Theme.
   *
   * This allows an object to have some control over how it is themed. For
   * most objects, this is implemented as a simple callback to a specific
   * rendering method in the specified Theme - but this may not be appropriate
   * for all renderable objects.
   *
   * @param Theme $theme the Theme to render this object with.
   * @return string this object rendered in the specified Theme.
   */
  public function render( Theme $theme );

}
