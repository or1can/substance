<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2014 - 2015 Kevin Rogers
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

namespace Substance\Core\Database\SQL\Components;

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\SQL\Column;
use Substance\Core\Database\SQL\Component;
use Substance\Core\Database\SQL\Query;

/**
 * Represents a component list in a query.
 *
 * e.g. the
 *     column1, column2
 * part of
 *     SELECT column1, column2 FROM table
 */
class ComponentList implements Component {

  /**
   * @var Component[] list of components
   */
  protected $components = array();

  /**
   * Adds the component to the list.
   *
   * @param Component $component the component to add to the list.
   */
  public function add( Component $component ) {
    $this->components[] = $component;
  }

  public function __toString() {
    $parts = array();
    foreach ( $this->columns as $column ) {
      $parts[] = (string) $column;
    }
    return implode( ', ', $parts );
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::build()
   */
  public function build( Database $database ) {
    return $database->buildComponentList( $this );
  }

  /**
   * Returns this component lists components.
   *
   * @return Component[] an array of this compenent lists components.
   */
  public function getComponents() {
    return $this->components;
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Component::define()
   */
  public function define( Query $query ) {
    // Nothing to do.
  }

}
