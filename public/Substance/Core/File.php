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

use Substance\Core\Alert\Alert;

/**
 * A File module to represent files.
 */
class File {

  protected $uri = NULL;

  protected $filename = NULL;

  /**
   * Constructs a new File object for the specified path.
   *
   * @param unknown $path
   */
  public function __construct( $uri ) {
    if ( is_file( $uri ) ) {
      $this->uri = $uri;
      $this->filename = basename( $uri );
    } else {
      throw Alert::alert( 'Not a file', 'Supplied URI does not point to a file' )
        ->culprit( 'uri', $uri );
    }
  }

  public static function scanDirectory( $dir, $pattern, $callback = NULL ) {
    if ( is_dir( $dir ) ) {
      $handle = opendir( $dir );

    }
  }

}
