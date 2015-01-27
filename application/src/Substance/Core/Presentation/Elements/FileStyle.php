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

namespace Substance\Core\Presentation\Elements;

use Substance\Core\File;
use Substance\Core\Presentation\Theme;

/**
 * A simple file style element.
 */
class FileStyle extends Style {

  /**
   * The markup contents.
   *
   * @var File
   */
  protected $file;

  /**
   * Returns this elements file.
   *
   * @return File the file.
   */
  public function getFile() {
    return $this->file;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Presentation\Element::render()
   */
  public function render( Theme $theme ) {
    return $theme->renderFileStyle( $this );
  }

  /**
   * Set the file for this element.
   *
   * @param File $file the file
   * @return self this element so methods can be chained.
   */
  public function setFile( File $file ) {
    $this->file = $file;
    return $this;
  }

}
