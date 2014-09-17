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
 * The ElementId class represents a unique identifier for an Element. The use
 * of this class ensures that no two Id's can be the same.
 */
class ElementId {

  /**
   * An array mapping a prefix the its corresponding ElementId.
   *
   * @var array
   */
  protected static $existing_ids = array();

  /**
   * The unique identifier.
   *
   * @var string
   */
  protected $id;

  /**
   * Hidden constructor, as this class should not be instantiated directly. Use
   * the getEnvironment method instead.
   *
   * @param string $id The
   */
  protected function __construct( $id ) {
    $this->id = $id;
  }

  public function __toString() {
    return $this->id;
  }

  /**
   * Returns a new, unique, ElementId for the specified prefix. For example,
   * the first time this method is called with prefix 'table', the returned
   * ElementId would be 'table-1', but the second time this method is called,
   * the returned ElementId would be 'table-2'.
   *
   * @param string $prefix the prefix for the unique element ID.
   * @return ElementId a new, unique, ElementID for the specifed prefix.
   */
  public static function newElementId( $prefix ) {
    $counter = 1;
    $sanitised_prefix = self::sanitise_prefix( $prefix );
    $unique_id = $sanitised_prefix . '-' . $counter;
    while ( array_key_exists( $unique_id, self::$existing_ids ) ) {
      $counter++;
      $unique_id = $sanitised_prefix . '-' . $counter;
    }
    // We've got our unique ID, so build, record and return the object for it.
    $element_id = new ElementId( $unique_id );
    self::$existing_ids[ $unique_id ] = $element_id;
    return $element_id;
  }

  public static function sanitise_prefix( $prefix ) {
    // In order to simplify using these IDs in HTML, we remove the
    // 'Substance\Core\Presentation\Elements\' prefix that commonly occurs for
    // built-in elements. Third party elements will keep their prefix.
    $simplified_prefix = str_replace( 'Substance\\Core\\Presentation\\Elements\\', '', $prefix );
    return strtolower( preg_replace( '/[^A-Za-z0-9\-_\:\.]/', '_', $simplified_prefix ) );
  }

}
