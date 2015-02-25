<?php
/* Substance - Content Management System and application framework.
 * Copyright (C) 2015 Kevin Rogers
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

namespace Substance\Core\Database\Schema\Types;

use Substance\Core\Database\Schema\Database;
use Substance\Core\Database\Schema\Type;
use Substance\Core\Alert\Alerts\IllegalValueAlert;

/**
 * Represents a numeric data type.
 */
class Numeric implements Type {

  /**
   * @var int this numeric's precision.
   */
  protected $precision;

  /**
   * @var int this numeric's scale.
   */
  protected $scale;

  /**
   * Constructs a new numeric type.
   *
   * @param int $precision the precision.
   * @param int $scale the scale.
   * @throws DatabaseAlert if the scale is greater than the precision.
   */
  public function __construct( $precision, $scale ) {
    if ( $scale > $precision ) {
      throw IllegalValueAlert::illegal_value('Scale cannot be greater than precision')
        ->culprit( 'precision', $precision )
        ->culprit( 'scale', $scale );
    } else {
      $this->precision = $precision;
      $this->scale = $scale;
    }
  }

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\SQL\Buildable::build()
   */
  public function build( Database $database ) {
    return $database->buildNumeric( $this );
  }

  /**
   * Returns this numeric's precision.
   *
   * @return Size this numeric's precision.
   */
  public function getPrecision() {
    return $this->precision;
  }

  /**
   * Returns this numeric's scale.
   *
   * @return Size this numeric's scale.
   */
  public function getScale() {
    return $this->scale;
  }

}
