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
 * A Folder class to represent folders.
 */
class Folder {

  protected $uri = NULL;

  /**
   * Constructs a new File object for the specified path.
   *
   * @param unknown $path
   */
  public function __construct( $uri ) {
    if ( is_dir( $uri ) ) {
      $this->uri = $uri;
    } else {
      throw Alert::alert( 'Not a folder', 'Supplied URI does not point to a folder' )
        ->culprit( 'uri', $uri );
    }
  }

  /**
   * Finds files matching the supplied glob pattern.
   *
   * The search is recursive and will look at all files in this folder and then
   * recurse into all sub-folders.
   *
   * @param string $file_glob the glob pattern for the files you want to find
   * @param boolean $recurse TRUE to recurse into sub-folders and FALSE
   * otherwise.
   * @param int $depth the number of folders deep to recurse into while finding
   * or NULL for infinite depth.
   */
  public function find( $file_glob, $recurse = TRUE, $depth = NULL ) {
    $files = array();
    $handle = opendir( $this->uri );
    while ( ( $filename = readdir( $handle ) ) !== FALSE ) {
      // Do not search '.' or '..' as this will cause recursion.
      if ( $filename !== '.' && $filename !== '..' ) {
        $path = $this->uri . '/' . $filename;
        if ( is_dir( $path ) ) {
          // It's another directory, so can it's contents.
          if ( $recurse && ( is_null( $depth ) || $depth > 0 ) ) {
            $folder = new Folder( $path );
            $files = array_merge( $files, $folder->find( $file_glob, $depth - 1 ) );
          }
        } else if ( fnmatch( $file_glob, $filename ) ) {
          // It's not a directory and it matches the glob pattern, so add it.
          $file = new File( $path );
          $files[ $path ] = $file;
        }
      }
    }
    return $files;
  }

}
