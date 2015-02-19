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

namespace Substance\Core\Database\Drivers\MySQL\Schema\Types;

use Substance\Core\Alert\Alerts\IllegalValueAlert;
use Substance\Core\Database\Schema\Size;
use Substance\Core\Database\Schema\Types\Integer;

/**
 * Represents a data type.
 */
class MySQLInteger extends Integer {

  /* (non-PHPdoc)
   * @see \Substance\Core\Database\Schema\Type::getName()
   */
  public function getName() {
    switch ( $this->size->getValue() ) {
      case Size::TINY:
        return 'TINYINT';
        break;
      case Size::SMALL:
        return 'SMALLINT';
        break;
      case Size::MEDIUM:
        return 'MEDIUMINT';
        break;
      case Size::NORMAL:
        return 'INTEGER';
        break;
      case Size::BIG:
        return 'BIGINT';
        break;
      default:
        throw IllegalValueAlert::illegal_value('Only tiny, small, medium, normal and big sizes are supported')
          ->culprit( 'size', $this->size );
    }
  }

}
